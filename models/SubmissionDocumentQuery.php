<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionDocument]].
 *
 * @see SubmissionDocument
 */
class SubmissionDocumentQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionDocument[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionDocument|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_document.deleted' => $deleted]);
    }

    public function isCertificate($certificate = TRUE) {
        return $this->andWhere(['submission_document.is_certificate' => $certificate]);
    }

    public function isReport($report = TRUE) {
        return $this->andWhere(['document.is_report' => $report]);
    }

    public function notId($id) {
        return $this->andWhere(['not', ['submission_document.id' => $id]]);
    }

    public function status($status) {
        return $this->andWhere(['submission_document.status' => $status]);
    }

    public function submission($submission) {
        return $this->andWhere(['submission_document.submission_id' => $submission]);
    }

    public function documents($documents) {
        return $this->andWhere(['submission_document.document_id' => $documents]);
    }

    public function submissionTypeRole($roleId) {
        return $this->andWhere(['document_submission_type.role_id' => $roleId]);
    }

    public function notInDocuments($documents) {
        return $this->andWhere(['or', ['not', ['submission_document.document_id' => $documents]], ['submission_document.document_id' => NULL]]);
    }

    public function name($name) {
        return $this->andWhere(['submission_document.name' => $name]);
    }

    public function groupDoc($groupId) {
        return $this->andWhere(['submission_document.group_doc_id' => $groupId]);
    }

    public function volunteerId($volunteerId) {
        return $this->andWhere(['submission_document.volunteer_id' => $volunteerId]);
    }

    public function submissionEventId($submissionEventId) {
        return $this->andWhere(['submission_document.submission_event_id' => $submissionEventId]);
    }

    public function sdCrecId($sdCrecId) {
        return $this->andWhere(['submission_document.sd_crec_id' => $sdCrecId]);
    }

    public function hasSdCrecId($has = true) {
        if ($has) {
            return $this->andWhere(['not', ['submission_document.sd_crec_id' => \null]]);
        }
        return $this->andWhere(['submission_document.sd_crec_id' => \null]);
    }

    public function hasFile($has = TRUE) {
        if ($has) {
            return $this->andWhere(['not',
                        ['submission_document.file_name' => null]
            ]);
        } else {
            return $this->andWhere(['submission_document.file_name' => null]);
        }
    }

    public function hasDocument($has = TRUE) {
        if ($has) {
            return $this->andWhere(['not', ['submission_document.document_id' => null]]);
        } else {
            return $this->andWhere(['submission_document.document_id' => null]);
        }
    }

}
