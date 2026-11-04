<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectQuestionChoice]].
 *
 * @see ProjectQuestionChoice
 */
class ProjectQuestionChoiceQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ProjectQuestionChoice[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuestionChoice|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_question_choice.deleted' => $deleted]);
    }

}
