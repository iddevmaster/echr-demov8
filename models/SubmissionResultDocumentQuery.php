<?php

namespace app\models;

use yii\helpers\VarDumper;

/**
 * This is the ActiveQuery class for [[SubmissionResultDocument]].
 *
 * @see SubmissionResultDocument
 */
class SubmissionResultDocumentQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionResultDocument[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionResultDocument|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_result_document.deleted' => $deleted]);
    }

    public function resultDocument($resultDocumentId) {
        return $this->andWhere(['submission_result_document.result_document_id' => $resultDocumentId]);
    }

    public function revise($reviseId) {
        return $this->andWhere(['submission_result_document.submission_committee_revise_id' => $reviseId]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['submission_result_document.submission_id' => $submissionId]);
    }

    public function notInDocuments($documents) {
        $cond = ['or', ['not', ['submission_result_document.result_document_id' => $documents]], ['submission_result_document.result_document_id' => NULL]];
        if (empty($documents)) {
            $cond = ['submission_result_document.result_document_id' => NULL];
        }
        return $this->andWhere($cond);
    }

    public function coaToken($token) {
        return $this->andWhere(['submission_result_document.coa_token' => $token]);
    }

    public function srdCrecId($srdCrecId) {
        return $this->andWhere(['submission_result_document.srd_crec_id' => $srdCrecId]);
    }

}
