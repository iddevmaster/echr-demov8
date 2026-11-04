<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Meeting]].
 *
 * @see Meeting
 */
class MeetingQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Meeting[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Meeting|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['meeting.deleted' => $deleted]);
    }

    public function person($PersonId) {
        $subQuery = (new \yii\db\Query())
                ->select('mp.id')
                ->from('meeting_person mp')
                ->innerJoin('person p', 'mp.person_id=p.id')
                ->andWhere('mp.meeting_id = meeting.id')
                ->andWhere(['mp.deleted' => FALSE, 'mp.person_id' => $PersonId]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function panel($panelId) {
        return $this->andWhere(['meeting.panel_id' => $panelId]);
    }

    public function year($year) {
        return $this->andWhere(['meeting.year' => $year]);
    }

    public function cSstatus($status) {
        return $this->andWhere(['meeting.checked_status' => $status]);
    }

    public function staffCheck($user) {
        return $this->andWhere(['meeting.checked_staff' => $user, 'meeting.checked_status' => Meeting::CS_PENDING]);
    }
        public function president($user) {
        return $this->andWhere(['meeting.checked_president' => $user, 'meeting.checked_status' => Meeting::CS_PRE_CHECKED]);
    }
        public function adminCheck() {
        return $this->andWhere(['meeting.checked_status' => Meeting::CS_PENDING]);
    }

    public function betweenDate($start, $end) {
        return $this->andWhere([
                    'and', ['>=', 'meeting.start_date', $start],
                    ['<=', 'meeting.start_date', $end],
        ]);
    }
    public function secretary($user) {
        return $this->andWhere([
                    'or', ['and', ['like', 'meeting.checked_secretary_first', $user], ['meeting.checked_status' => Meeting::CS_STAFF_CHECKED]],
                    ['and', ['like', 'meeting.checked_secretary_second', $user], ['meeting.checked_status' => Meeting::CS_PARTLY_CHECKED]],
        ]);
    }
    public function notStartYet() {
        return $this->andWhere([
                    '>', 'meeting.start_date', date('Y-m-d')
        ]);
    }

}
