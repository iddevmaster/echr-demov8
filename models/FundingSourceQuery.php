<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[FundingSource]].
 *
 * @see FundingSource
 */
class FundingSourceQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return FundingSource[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FundingSource|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['funding_source.deleted' => $deleted]);
    }

    public function crecId($id) {
        return $this->andWhere('FIND_IN_SET(:id, funding_source.crec_id)', [':id' => $id]);
    }

}
