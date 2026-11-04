<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionType]].
 *
 * @see SubmissionType
 */
class SubmissionTypeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionType|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_type.deleted' => $deleted]);
    }

    public function internal($internal = TRUE) {
        return $this->andWhere(['submission_type.internal' => $internal]);
    }

    public function group($group) {
        return $this->andWhere(['submission_type.submission_type_group_id' => $group]);
    }

    public function resolution($resolution) {
        return $this->andWhere(['submission_type.resolution' => $resolution]);
    }

    public function crecId($crecId)
    {
        return $this->andWhere(['submission_type.crec_id' => $crecId]);
    }

    public function isNew($isNew = true)
    {
        return $this->andWhere(['submission_type.is_new' => $isNew]);
    }
}
