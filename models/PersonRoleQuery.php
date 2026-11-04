<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PersonRole]].
 *
 * @see PersonRole
 */
class PersonRoleQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return PersonRole[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PersonRole|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['person_role.deleted' => $deleted]);
    }

    public function role($roleId) {
        return $this->andWhere(['person_role.role_id' => $roleId]);
    }

    public function person($person) {
        return $this->andWhere(['person_role.person_id' => $person]);
    }

    public function panel($panelId) {
        return $this->andWhere(['person_role_panel.deleted' => FALSE, 'person_role_panel.panel_id' => $panelId]);
    }

}
