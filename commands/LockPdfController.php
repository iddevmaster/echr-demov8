<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\SubmissionResultDocument;

/**
 * Batch lock PDF ของ SubmissionResultDocument ที่ยังไม่ถูก lock
 * Usage:
 *   php yii lock-pdf/lock-all
 *   php yii lock-pdf/lock-all --dryRun=1
 */
class LockPdfController extends Controller
{
    public $dryRun = false;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['dryRun']);
    }

    public function actionLockAll()
    {
        $query = SubmissionResultDocument::find()
            ->where(['is_locked' => 0, 'deleted' => 0])
            ->andWhere(['not', ['document_file' => null]])
            ->andWhere(['<>', 'document_file', '']);

        $total = $query->count();
        $this->stdout("Found {$total} PDFs to lock\n");

        $ok = 0;
        $fail = 0;
        $skip = 0;

        foreach ($query->each(50) as $doc) {
            /** @var SubmissionResultDocument $doc */
            $path = $doc->filePath;

            if (!file_exists($path)) {
                $this->stdout("[SKIP] id={$doc->id}: file not found ({$doc->document_file})\n");
                $skip++;
                continue;
            }

            $ext = strtolower(pathinfo($doc->document_file, PATHINFO_EXTENSION));
            if ($ext !== 'pdf') {
                $this->stdout("[SKIP] id={$doc->id}: not a pdf ({$doc->document_file})\n");
                $skip++;
                continue;
            }

            if ($this->dryRun) {
                $this->stdout("[DRY] would lock id={$doc->id}: {$doc->document_file}\n");
                continue;
            }

            try {
                if (Yii::$app->pdfLocker->lock($path)) {
                    $doc->is_locked = 1;
                    $doc->save(false, ['is_locked', 'updated_at', 'updated_by']);
                    $ok++;
                    $this->stdout("[OK] id={$doc->id}\n");
                } else {
                    $fail++;
                    $this->stdout("[FAIL] id={$doc->id}\n");
                }
            } catch (\Exception $e) {
                $fail++;
                $this->stdout("[ERR] id={$doc->id}: {$e->getMessage()}\n");
            }
        }

        $this->stdout("\nDone: ok={$ok} fail={$fail} skip={$skip}\n");
        return ExitCode::OK;
    }
}
