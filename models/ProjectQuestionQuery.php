<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectQuestion]].
 *
 * @see ProjectQuestion
 */
class ProjectQuestionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ProjectQuestion[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuestion|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_question.deleted' => $deleted]);
    }
}
