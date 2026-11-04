<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Agenda]].
 *
 * @see Agenda
 */
class AgendaQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Agenda[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Agenda|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['agenda.deleted' => $deleted]);
    }

    public function parentAgenda($parent) {
        return $this->andWhere(['agenda.parent_id' => $parent]);
    }

    public function hasParent() {
        return $this->andWhere(['not', ['agenda.parent_id' => null]]);
    }
    
    public function isSubmission($is = TRUE) {
        return $this->andWhere(['agenda.is_submission' => $is]);
    }

}
