<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectAgendaQuestion]].
 *
 * @see ProjectAgendaQuestion
 */
class ProjectAgendaQuestionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ProjectAgendaQuestion[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectAgendaQuestion|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_agenda_question.deleted' => $deleted]);
    }

    public function questionDeleted($deleted = TRUE) {
        return $this->andWhere(['project_question.deleted' => $deleted]);
    }

}
