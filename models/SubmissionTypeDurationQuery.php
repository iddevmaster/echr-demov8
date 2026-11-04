<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionTypeDuration]].
 *
 * @see SubmissionTypeDuration
 */
class SubmissionTypeDurationQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SubmissionTypeDuration[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionTypeDuration|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_type_duration.deleted' => $deleted]);
    }

}
