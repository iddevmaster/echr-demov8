<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[QuestionnaireTitle]].
 *
 * @see QuestionnaireTitle
 */
class QuestionnaireTitleQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return QuestionnaireTitle[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return QuestionnaireTitle|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['questionnaire_title.deleted' => $deleted]);
    }
    public function submissionType($submmissionTypeId) {
        return $this->andWhere(['questionnaire_title.submission_type_id' => $submmissionTypeId]);
    }
}
