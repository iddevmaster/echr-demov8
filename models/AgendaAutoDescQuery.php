<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AgendaAutoDesc]].
 *
 * @see AgendaAutoDesc
 */
class AgendaAutoDescQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return AgendaAutoDesc[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AgendaAutoDesc|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['agenda_auto_desc.deleted' => $deleted]);
    }

}
