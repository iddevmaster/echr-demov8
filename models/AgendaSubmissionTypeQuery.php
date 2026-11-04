<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AgendaSubmissionType]].
 *
 * @see AgendaSubmissionType
 */
class AgendaSubmissionTypeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return AgendaSubmissionType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AgendaSubmissionType|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['agenda_submission_type.deleted' => $deleted]);
    }

    public function submissionType($type) {
        return $this->andWhere(['agenda_submission_type.submission_type_id' => $type]);
    }
    
    public function resolutionLabel($label) {
        return $this->andWhere(['submission_type.resolution_label' => $label]);
    }

}
