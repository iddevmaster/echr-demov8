<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionTypeAssessForm]].
 *
 * @see SubmissionTypeAssessForm
 */
class SubmissionTypeAssessFormQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SubmissionTypeAssessForm[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionTypeAssessForm|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_type_assess_form.deleted' => $deleted]);
    }
}
