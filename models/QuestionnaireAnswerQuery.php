<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[QuestionnaireAnswer]].
 *
 * @see QuestionnaireAnswer
 */
class QuestionnaireAnswerQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return QuestionnaireAnswer[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return QuestionnaireAnswer|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['questionnaire_answer.deleted' => $deleted]);
    }

    public function questionnaireTitle($questionnaireTitleId) {
        return $this->andWhere(['questionnaire_answer.questionnaire_title_id' => $questionnaireTitleId]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['questionnaire_answer.submission_id' => $submissionId]);
    }

    public function submissionCommittee($comSubmissionId) {
        return $this->andWhere(['questionnaire_answer.submission_committee_id' => $comSubmissionId]);
    }

}
