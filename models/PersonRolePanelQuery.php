<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PersonRolePanel]].
 *
 * @see PersonRolePanel
 */
class PersonRolePanelQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return PersonRolePanel[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PersonRolePanel|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['person_role_panel.deleted' => $deleted]);
    }

    public function isRegular($regular = TRUE) {
        return $this->andWhere(['person_role_panel.is_regular' => $regular]);
    }

    public function panel($panelId) {
        return $this->andWhere(['person_role_panel.panel_id' => $panelId]);
    }

    public function role($roleId) {
        return $this->andWhere(['person_role.role_id' => $roleId]);
    }

    public function person($personId) {
        return $this->andWhere(['person_role.person_id' => $personId]);
    }

}
