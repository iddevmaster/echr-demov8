<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectAgendaAnswer]].
 *
 * @see ProjectAgendaAnswer
 */
class ProjectAgendaAnswerQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ProjectAgendaAnswer[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectAgendaAnswer|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_agenda_answer.deleted' => $deleted]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['project_agenda_answer.submission_id' => $submissionId]);
    }

    public function projectQuestion($projectQuestionId) {
        return $this->andWhere(['project_agenda_answer.project_question_id' => $projectQuestionId]);
    }

}
