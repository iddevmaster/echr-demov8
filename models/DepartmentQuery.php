<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Department]].
 *
 * @see Department
 */
class DepartmentQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Department[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Department|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['department.deleted' => $deleted]);
    }

    public function organization($organization) {
        return $this->andWhere(['department.organization_id' => $organization]);
    }

    public function crecId($crecId) {
        return $this->andWhere(['department.crec_id' => $crecId]);
    }

}
