<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MeetingRoom]].
 *
 * @see MeetingRoom
 */
class MeetingRoomQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return MeetingRoom[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MeetingRoom|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['meeting_room.deleted' => $deleted]);
    }

}
