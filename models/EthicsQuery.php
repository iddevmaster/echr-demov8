<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Ethics]].
 *
 * @see Ethics
 */
class EthicsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return Ethics[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Ethics|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['ethics.deleted' => $deleted]);
    }

}
