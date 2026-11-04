<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[GroupDoc]].
 *
 * @see GroupDoc
 */
class GroupDocQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return GroupDoc[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return GroupDoc|array|null
     */
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['group_doc.deleted' => $deleted]);
    }

    public function one($db = null) {
        return parent::one($db);
    }

}
