<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectType]].
 *
 * @see ProjectType
 */
class ProjectTypeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ProjectType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectType|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_type.deleted' => $deleted]);
    }
}
