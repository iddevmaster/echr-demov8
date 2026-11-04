<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CommitteePosition]].
 *
 * @see CommitteePosition
 */
class CommitteePositionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return CommitteePosition[]|array
     */
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['committee_position.deleted' => $deleted]);
    }
    public function isCancel($status = TRUE) {
        return $this->andWhere(['committee_position.status' => $status]);
    }
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CommitteePosition|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

}
