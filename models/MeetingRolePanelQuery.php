<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MeetingRolePanel]].
 *
 * @see MeetingRolePanel
 */
class MeetingRolePanelQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return MeetingRolePanel[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MeetingRolePanel|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['meeting_role_panel.deleted' => $deleted]);
    }

    public function panel($panelId) {
        return $this->andWhere(['meeting_role_panel.panel_id' => $panelId]);
    }

}
