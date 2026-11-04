<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeviationType]].
 *
 * @see DeviationType
 */
class DeviationTypeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return DeviationType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviationType|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['deviation_type.deleted' => $deleted]);
    }

}
