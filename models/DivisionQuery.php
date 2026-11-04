<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Division]].
 *
 * @see Division
 */
class DivisionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Division[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Division|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['division.deleted' => $deleted]);
    }

    public function department($department) {
        return $this->andWhere(['division.department_id' => $department]);
    }
}
