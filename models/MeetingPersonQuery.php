<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MeetingPerson]].
 *
 * @see MeetingPerson
 */
class MeetingPersonQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return MeetingPerson[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MeetingPerson|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['meeting_person.deleted' => $deleted]);
    }

    public function person($personId) {
        return $this->andWhere(['meeting_person.person_id' => $personId]);
    }

    public function personOrLastName($personId, $lastName) {
        return $this->andWhere(['or', ['meeting_person.person_id' => $personId], ['person.last_name' => $lastName]]);
    }

    public function meeting($meetingId) {
        return $this->andWhere(['meeting_person.meeting_id' => $meetingId]);
    }

    public function isPaediatrician($is = TRUE) {
        return $this->andWhere(['person.is_paediatrician' => $is]);
    }

    public function jobCategory($jobCategory) {
        return $this->andWhere(['person.job_category_id' => $jobCategory]);
    }

    public function isExternal($is = TRUE) {
        return $this->andWhere(['person.is_external' => $is]);
    }

    public function role($role) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('person_role pr')
                ->innerJoin('person_role_panel prp', 'prp.person_role_id=pr.id')
                ->andWhere(['prp.deleted' => 0, 'pr.deleted' => 0])
                ->andWhere(['pr.role_id' => $role])
                ->andWhere('prp.panel_id=meeting.panel_id');
        return $this->andWhere(['exists', $subQuery]);
    }

    public function roleName($role) {
        return $this->andWhere(['meeting_person.role_name' => $role]);
    }
}
