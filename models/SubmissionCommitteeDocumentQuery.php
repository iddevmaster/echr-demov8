<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionCommitteeDocument]].
 *
 * @see SubmissionCommitteeDocument
 */
class SubmissionCommitteeDocumentQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionCommitteeDocument[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionCommitteeDocument|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function notId($id) {
        return $this->andWhere(['not', ['submission_committee_document.id' => $id]]);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_committee_document.deleted' => $deleted]);
    }

    public function submission($submission) {
        return $this->andWhere(['submission_committee_document.submission_id' => $submission]);
    }

    public function submissionCommittee($sCommitteeId) {
        return $this->andWhere(['submission_committee_document.submission_committee_id' => $sCommitteeId]);
    }

    public function documents($documents) {
        return $this->andWhere(['submission_committee_document.document_id' => $documents]);
    }

    public function notInDocuments($documents) {
        return $this->andWhere(['or', ['not', ['submission_committee_document.document_id' => $documents]], ['submission_committee_document.document_id' => NULL]]);
    }

    public function notInDocument() {
        return $this->andWhere(['submission_committee_document.document_id' => NULL]);
    }

    public function crecDocument($documentId) {
        return $this->andWhere(['submission_committee_document.crec_document_id' => $documentId]);
    }
        public function notCrec() {
        return $this->andWhere(['submission_committee_document.crec_document_id' => NULL]);
    }

}
