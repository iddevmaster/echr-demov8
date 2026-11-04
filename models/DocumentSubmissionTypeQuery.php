<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DocumentSubmissionType]].
 *
 * @see DocumentSubmissionType
 */
class DocumentSubmissionTypeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return DocumentSubmissionType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return DocumentSubmissionType|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['document_submission_type.deleted' => $deleted]);
    }

    public function isRequire($require = TRUE) {
        return $this->andWhere(['document_submission_type.is_require' => $require]);
    }

    public function isEvent($event = true) {
        return $this->andWhere(['document_submission_type.is_event' => $event]);
    }

    public function submissionType($submissionType) {
        return $this->andWhere(['document_submission_type.submission_type_id' => $submissionType]);
    }

    public function refSubmissionType($submissionType) {
        return $this->andWhere(['document_submission_type.ref_submission_type_id' => $submissionType]);
    }

    public function submissionTypeRole($roleId) {
        return $this->andWhere(['document_submission_type.role_id' => $roleId]);
    }

    public function document($document) {
        return $this->andWhere(['document_submission_type.document_id' => $document]);
    }

    public function role($roleId) {
        return $this->andWhere(['document_submission_type.role_id' => $roleId]);
    }

    public function sort($sort) {
        return $this->andFilterCompare('document_submission_type.sort', $sort);
    }

    public function committeePosition($cpId) {
        return $this->andWhere(['document_submission_type.committee_position_id' => $cpId]);
    }

    public function isApi($isApi = true)
    {
        return $this->andWhere(['document_submission_type.is_api' => $isApi]);
    }
}
