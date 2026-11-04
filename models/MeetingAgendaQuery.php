<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MeetingAgenda]].
 *
 * @see MeetingAgenda
 */
class MeetingAgendaQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return MeetingAgenda[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MeetingAgenda|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['meeting_agenda.deleted' => $deleted]);
    }

    public function agenda($agendaId) {
        return $this->andWhere(['meeting_agenda.agenda_id' => $agendaId]);
    }

    public function parentAgenda($parent) {
        return $this->andWhere(['meeting_agenda.parent_id' => $parent]);
    }

    public function meeting($meetingId) {
        return $this->andWhere(['meeting_agenda.meeting_id' => $meetingId]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['meeting_agenda.submission_id' => $submissionId]);
    }

    public function hasSubmission($has = TRUE) {
        if ($has) {
            return $this->andWhere(['not', ['meeting_agenda.submission_id' => NULL]]);
        } else {
            return $this->andWhere(['meeting_agenda.submission_id' => NULL]);
        }
    }

    public function projectResearcher($personId) {
        return $this->andWhere(['project_researcher.person_id' => $personId, 'project_researcher.deleted' => FALSE]);
    }

    public function isSubmission($is = TRUE) {
        if ($is) {
            return $this->andWhere(['agenda.is_submission' => $is]);
        } else {
            return $this->andWhere(['not', ['agenda.is_submission' => $is]]);
        }
    }

    public function group($group) {
        return $this->andWhere(['meeting_agenda.meeting_id' => $group]);
    }

    public function sortLabel($sortLabel) {
        return $this->andWhere("meeting_agenda.sort_label LIKE '{$sortLabel}%'");
    }
    public function resolutionNotnull() {
//        return $this->andWhere(['meeting_agenda.resolution' => null]);
                return $this->andWhere([
                    'or', ['meeting_agenda.resolution' => null],
                    ['meeting_agenda.resolution' => ''],
        ]);
    }   
    public function notCOI() {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
//                ->from('submission_project_researcher spr')
                ->from('project_researcher pr')
                ->innerJoin('submission_coi_person scp', 'pr.person_id = scp.person_id')
                ->andWhere('pr.submission_id = scp.submission_id')
                ->andWhere('pr.submission_id = meeting_agenda.submission_id')
                ->andWhere(['pr.deleted' => 0, 'scp.deleted' => 0, 'scp.person_id' => \Yii::$app->user->identity->person->id]);
        return $this->andWhere(['not exists', $subQuery]);
    }
    
    
        public function notCOIMeeting() {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
//                ->from('submission_project_researcher spr')
                ->from('meeting_person pr')
                ->innerJoin('submission_coi_person scp', 'pr.person_id = scp.person_id')
//                ->andWhere('pr.submission_id = scp.submission_id')
                ->andWhere('scp.submission_id = meeting_agenda.submission_id')
                ->andWhere(['pr.deleted' => 0, 'scp.deleted' => 0, 'scp.person_id' => \Yii::$app->user->identity->person->id]);
        return $this->andWhere(['not exists', $subQuery]);
    }
    
    
    public function hasCOI($personId, $lastName=null) {
        $subQuery = (new \yii\db\Query())
                ->select('project_researcher.id')
//                ->from('submission_project_researcher spr')
                ->from('submission_coi_person scp')
                ->andWhere('project_researcher.submission_id = scp.submission_id')
                ->andWhere('project_researcher.submission_id = meeting_agenda.submission_id')
//                ->andWhere('')
                ->andWhere(['project_researcher.deleted' => 0, 'scp.deleted' => 0, 'scp.person_id' => $personId]);
        $or = ['or', ['project_researcher.person_id' => $personId, 'project_researcher.deleted' => 0], ['exists', $subQuery]];
        if (isset($lastName)) {
            $or[] = ['person.last_name' => $lastName];
        }
        return $this->andWhere($or);
    }
}
