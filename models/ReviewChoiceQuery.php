<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ReviewChoice]].
 *
 * @see ReviewChoice
 */
class ReviewChoiceQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ReviewChoice[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ReviewChoice|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['review_choice.deleted' => $deleted]);
    }
    
    public function parent($parentId) {
        return $this->andWhere(['review_choice.parent_id' => $parentId]);
    }
}
