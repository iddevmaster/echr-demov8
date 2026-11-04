<?php

namespace app\models;

use yii\db\Query;

/**
 * This is the ActiveQuery class for [[Person]].
 *
 * @see Person
 */
class PersonQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Person[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Person|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['person.deleted' => $deleted]);
    }

    public function isResearcherCrec($is = true)
    {
        return $this->andWhere(['person.is_researcher_crec' => $is]);
    }

    public function active($active = TRUE) {
        $status = User::STATUS_ACTIVE;
        if (!$active) {
            $status = User::STATUS_DELETED;
        }
        return $this->andWhere(['user.status' => $status]);
    }

    public function idcard($idcard) {
        return $this->andWhere(['person.idcard_no' => $idcard]);
    }

    public function email($email) {
        return $this->andWhere(['person.email' => $email]);
    }

    public function isInProject($in = TRUE, $project) {
        $subQuery = (new Query())->select('id')->from('project_researcher')->andWhere(['deleted' => FALSE, 'project_id' => $project])->andWhere('project_researcher.person_id=person.id')->andWhere(['project_researcher.acknowledge_status' => [ProjectResearcher::STATUS_PENDING_ACK, ProjectResearcher::STATUS_ACCEPTED]]);
        return $this->andWhere([($in ? 'EXISTS' : 'NOT EXISTS'), $subQuery]);
    }

    public function isInSubmission($in = TRUE, $submission) {
        $subQuery = (new Query())->select('id')->from('project_researcher')->andWhere(['deleted' => FALSE, 'submission_id' => $submission])->andWhere('project_researcher.person_id=person.id')->andWhere(['project_researcher.acknowledge_status' => [ProjectResearcher::STATUS_PENDING_ACK, ProjectResearcher::STATUS_ACCEPTED]]);
        return $this->andWhere([($in ? 'EXISTS' : 'NOT EXISTS'), $subQuery]);
    }

    public function isInProjectConsultant($in = TRUE, $project) {
        $subQuery = (new Query())->select('id')->from('project_consultant')->andWhere(['deleted' => FALSE, 'project_id' => $project])->andWhere('project_consultant.person_id=person.id')->andWhere(['project_consultant.acknowledge_status' => [ProjectConsultant::STATUS_PENDING_ACK, ProjectConsultant::STATUS_ACCEPTED]]);
        return $this->andWhere([($in ? 'EXISTS' : 'NOT EXISTS'), $subQuery]);
    }

    public function isInSubmissionConsultant($in = TRUE, $submission) {
        $subQuery = (new Query())->select('id')->from('project_consultant')->andWhere(['deleted' => FALSE, 'submission_id' => $submission])->andWhere('project_consultant.person_id=person.id')->andWhere(['project_consultant.acknowledge_status' => [ProjectConsultant::STATUS_PENDING_ACK, ProjectConsultant::STATUS_ACCEPTED]]);
        return $this->andWhere([($in ? 'EXISTS' : 'NOT EXISTS'), $subQuery]);
    }

    public function role($role) {
        return $this->andWhere(['person_role.deleted' => FALSE, 'person_role.role_id' => $role]);
    }

    public function panel($panelId) {
        return $this->andWhere(['person_role_panel.deleted' => FALSE, 'person_role_panel.panel_id' => $panelId]);
    }

    public function isRegular($regular = TRUE) {
        return $this->andWhere(['person_role_panel.deleted' => FALSE, 'person_role_panel.is_regular' => $regular]);
    }

    public function lastName($nameTh, $nameEn) {
        return $this->andWhere(['or', ['person.last_name' => $nameTh], ['person.last_name_eng' => $nameEn]]);
    }

    public function notInMeeting($meetingId) {
        $subQuery = (new Query())
                ->select('id')
                ->from('meeting_person mp')
                ->andWhere(['mp.deleted' => 0])
                ->andWhere(['mp.meeting_id' => $meetingId])
                ->andWhere('mp.person_id = person.id');
        return $this->andWhere(['not exists', $subQuery]);
    }

    public function notId($id) {
        return $this->andWhere(['not', ['person.id' => $id]]);
    }

    public function isInPersonRole($roleId) {
        $roleId = \is_array($roleId) ? $roleId : [$roleId];
        $subQuery = (new Query())->select('id')->from('person_role')->andWhere(['role_id' => $roleId])->andWhere('person_role.person_id=person.id')->andWhere(['person_role.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function crecDepartmentId($crecDepartmentId) {
        $isExists = Department::find()->isDeleted(false)->crecId($crecDepartmentId)->exists();
        if ($isExists) {
            return $this->andWhere(['department.crec_id' => $crecDepartmentId]);
        } else {
            return $this;
        }
    }

}
