<?php

namespace app\components;

use Yii;
use app\models\PersonDocumentAudit;
use app\components\DocumentStamper;

/**
 * Centralizes the electronic-signature signing logic so it can be reused from
 * multiple controllers (PersonController for CV, PersonTrainingController for
 * training documents).
 *
 * Behavior (per Phases 10–11): the original (un-stamped) file is preserved; when
 * the stamp is chosen the served file becomes a stamped output produced FROM the
 * original (so re-stamping never double-stamps); when it is not chosen the served
 * file reverts to the original. The original is never deleted; superseded stamped
 * files are cleaned up. An audit row is recorded each time.
 */
class EsignService {

    /**
     * Stamp (optional) + record the audit trail for a person's CV.
     *
     * @param \app\models\Person $person
     * @param \app\models\Person $signer
     * @param int $authMethod PersonDocumentAudit::AUTH_*
     * @param bool $applyStamp
     */
    public static function signCv($person, $signer, $authMethod, $applyStamp) {
        $dir = Yii::getAlias('@app/web/' . $person->cvPath);
        // Capture the original once (current cv_file is the raw upload on first sign).
        if (empty($person->cv_original_file)) {
            $person->cv_original_file = $person->cv_file;
        }
        $original = $person->cv_original_file;
        $previousServed = $person->cv_file;
        $stampApplied = 0;
        $stampType = null;

        if ($applyStamp) {
            $res = DocumentStamper::stampFile($dir, $original, self::stampLines($signer, $person->cv_updated_at));
            if ($res['success']) {
                $person->cv_file = $res['fileName'];
                $stampApplied = 1;
                $stampType = $res['stampType'];
            }
        } else {
            // Revert to the original.
            $person->cv_file = $original;
        }

        $person->cv_signed_at = date('Y-m-d H:i:s');
        $person->cv_signed_by = isset($signer) ? $signer->id : null;
        $person->save(FALSE);

        self::cleanupSupersededFile($dir, $previousServed, $person->cv_file, $original);

        PersonDocumentAudit::recordSigned(
                $person->id, PersonDocumentAudit::DOC_TYPE_CV, null, $person->cv_file, $signer, $authMethod,
                ['stampApplied' => $stampApplied, 'stampType' => $stampType]
        );
    }

    /**
     * Stamp (optional) + record the audit trail for a training document.
     * Mirrors signCv: original_file preserved, file follows the stamp choice.
     *
     * @param \app\models\PersonTraining $training
     * @param \app\models\Person $signer
     * @param int $authMethod PersonDocumentAudit::AUTH_*
     * @param bool $applyStamp
     */
    public static function signTraining($training, $signer, $authMethod, $applyStamp) {
        $dir = Yii::getAlias('@app/web/' . $training->path);
        if (empty($training->original_file)) {
            $training->original_file = $training->file;
        }
        $original = $training->original_file;
        $previousServed = $training->file;
        $stampApplied = 0;
        $stampType = null;

        if ($applyStamp) {
            $res = DocumentStamper::stampFile($dir, $original, self::stampLines($signer));
            if ($res['success']) {
                $training->file = $res['fileName'];
                $stampApplied = 1;
                $stampType = $res['stampType'];
            }
        } else {
            $training->file = $original;
        }
        $training->save(FALSE);

        self::cleanupSupersededFile($dir, $previousServed, $training->file, $original);

        PersonDocumentAudit::recordSigned(
                isset($signer) ? $signer->id : $training->person_id, PersonDocumentAudit::DOC_TYPE_TRAINING, $training->id, $training->file, $signer, $authMethod,
                ['stampApplied' => $stampApplied, 'stampType' => $stampType]
        );
    }

    /**
     * Best-effort removal of a superseded stamped file. Never deletes the
     * original or the currently-served file.
     */
    public static function cleanupSupersededFile($dir, $previous, $current, $original) {
        if (empty($previous) || $previous === $current || $previous === $original) {
            return;
        }
        $path = rtrim($dir, '/') . '/' . $previous;
        if (is_file($path)) {
            @unlink($path);
        }
    }

    /**
     * Lines (signer name, dates, statement) embedded in the stamp.
     *
     * @param \app\models\Person $signer
     * @param string|null $cvUpdatedAt CV last-update date (Y-m-d). When provided
     *   (CV signing), the stamp shows an extra "วันที่ปรับปรุงข้อมูลล่าสุด" line;
     *   omitted for training documents which have no CV-update date.
     */
    public static function stampLines($signer, $cvUpdatedAt = null) {
        $lines = [
            'signerName' => isset($signer) ? $signer->fullName : '',
            'signedAt' => date('d/m/Y'),
            'statement' => PersonDocumentAudit::CERTIFY_STATEMENT,
        ];
        if (!empty($cvUpdatedAt)) {
            $lines['updatedAt'] = date('d/m/Y', strtotime($cvUpdatedAt));
        }
        return $lines;
    }

}
