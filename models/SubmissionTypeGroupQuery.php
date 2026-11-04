<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionTypeGroup]].
 *
 * @see SubmissionTypeGroup
 */
class SubmissionTypeGroupQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionTypeGroup[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionTypeGroup|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_type_group.deleted' => $deleted]);
    }

}
