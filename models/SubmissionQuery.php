<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Submission]].
 *
 * @see Submission
 */
class SubmissionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Submission[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Submission|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function createdBy($createdBy) {
        return $this->andWhere(['submission.created_by' => $createdBy]);
    }
    
    public function onlySubmitted($only = true) {
        if ($only) {
            return $this->andFilterCompare('submission.status', '>' . Submission::STATUS_PENDING_SUBMISSION);
        }
        return $this;
    }
    
    public function submissionType($submissionTypeId) {
        return $this->andWhere(['submission.submission_type_id' => $submissionTypeId]);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission.deleted' => $deleted]);
    }
    public function isCrec($isCrec=TRUE){
        return $this->andWhere(['submission.is_submit_by_api' => $isCrec])->andWhere(['submission.crec_resolution'=> Submission::RESOLUTION_Y]);
    }

    public function statusGd() {
        return $this->andWhere(['>=', 'submission.status', Submission::STATUS_DOC_APPROVED])->andWhere(['<=', 'submission.status', Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT]);
    }

    public function status($status) {
        $customValue = Submission::getCustomStatusValue($status);
        if (isset($customValue)) {
            return $this->andWhere(['>=', 'submission.status', $customValue['min']])->andWhere(['<', 'submission.status', $customValue['max']]);
        } else {
            return $this->andWhere(['submission.status' => $status]);
        }
    }

    public function projectCoordinator($coordinatorId) {
       return $this->andFilterWhere(['or', ['=', 'submission.project_coordinator_id', $coordinatorId], ['=', 'submission.project_coordinator_2nd_id', $coordinatorId], ['=', 'submission.project_coordinator_3rd_id', $coordinatorId],['=', 'submission.project_viewer_id', $coordinatorId]]);
//        return $this->andWhere(['submission.project_coordinator_id' => $coordinatorId]);||  Yii::$app->user->id == $sub->project_viewer_id
    }

    public function comTypeSubmission() {
       return $this->andFilterWhere(['or', ['=', 'submission.submission_type_id', 1], ['=', 'submission.submission_type_id', 2], ['=', 'submission.submission_type_id', 6]]);
//        return $this->andWhere(['submission.project_coordinator_id' => $coordinatorId]);
    }    
    
    public function projectViewer($viewerId) {
        return $this->andWhere(['submission.project_viewer_id' => $viewerId]);
    }

    public function resolution($resolution) {
        return $this->andWhere(['submission.resolution' => $resolution]);
    }
        public function crecResolution($resolution) {
        return $this->andWhere(['submission.crec_resolution' => $resolution]);
    }

    public function isLegacy($isLegacy) {
        return $this->andWhere(['submission.is_legacy' => $isLegacy]);
    }

    public function staff($user) {
        return $this->andWhere(['submission.responsible_person' => $user]);
    }

    public function coordinator($user) {
        return $this->andWhere(['not', ['submission.project_coordinator_id' => null]]);
    }

    public function submissionTypeGroup($group) {
        return $this->andWhere(['submission_type.submission_type_group_id' => $group]);
    }

    public function refSubmissionTypeGroup($group,$submissionTypeId,$resolution)
    {
        $subQuery = (new \yii\db\Query())
            ->select('s.id')
            ->from('submission s')
            ->innerJoin('submission_type st', 's.submission_type_id = st.id')
            ->andWhere('s.id = submission.ref_submission_id')
            ->andWhere(['s.deleted' => FALSE])
            ->andWhere(['s.submission_type_id' => $submissionTypeId])
            ->andWhere(['s.resolution' => $resolution])
            ->andWhere(['st.submission_type_group_id' => $group]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function resolutionLabel($label) {
        return $this->andWhere(['submission_type.resolution_label' => $label]);
    }

    public function submissionTypeCon($status) {
        return $this->andWhere(['submission_type.meeting_consideration' => $status]);
    }

    public function hasMeetingAgenda() {
        $subQuery = (new \yii\db\Query())
                ->select('ma.id')
                ->from('meeting_agenda ma')
                ->andWhere('ma.submission_id = submission.id')
                ->andWhere(['ma.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function hasMeetingAgendaPanel($agendaId, $panelId) {
        $subQuery = (new \yii\db\Query())
                ->select('meeting_agenda.id')
                ->from('project,meeting_agenda')
                ->andWhere('meeting_agenda.submission_id = submission.id')
                ->andWhere('submission.project_id = project.id')
                ->andWhere(['submission.deleted' => FALSE, 'meeting_agenda.deleted' => FALSE, 'meeting_agenda.agenda_id' => $agendaId, 'project.panel_id' => $panelId]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function hasMeetingAgendaPanelTotal($agendaId) {
        $subQuery = (new \yii\db\Query())
                ->select('meeting_agenda.id')
                ->from('meeting_agenda')
                ->andWhere('meeting_agenda.submission_id = submission.id')
                ->andWhere(['submission.deleted' => FALSE, 'meeting_agenda.deleted' => FALSE, 'meeting_agenda.agenda_id' => $agendaId]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function hasMeetingAgendaPanelSum($panelId) {
        $subQuery = (new \yii\db\Query())
                ->select('meeting_agenda.id')
                ->from('project,meeting_agenda')
                ->andWhere('meeting_agenda.submission_id = submission.id')
                ->andWhere('submission.project_id = project.id')
                ->andWhere(['submission.deleted' => FALSE, 'meeting_agenda.deleted' => FALSE, 'project.panel_id' => $panelId]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function researcherName($researcherName) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->innerJoin('person p', 'pr.person_id=p.id')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['or', ['like', 'CONCAT(p.first_name, p.last_name)', $researcherName], ['like', 'CONCAT(p.first_name_eng, p.last_name_eng)', $researcherName]])
                ->andWhere(['pr.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function researcherOrg($researcherOrg) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->innerJoin('person p', 'pr.person_id=p.id')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['p.organization_id' => $researcherOrg])
                ->andWhere(['pr.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }    
    public function researcherDep($researcherDep) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->innerJoin('person p', 'pr.person_id=p.id')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['p.department_id' => $researcherDep])
                ->andWhere(['pr.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }    
    public function researcherDiv($researcherDiv) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->innerJoin('person p', 'pr.person_id=p.id')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['p.division_id' => $researcherDiv])
                ->andWhere(['pr.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }        
    
    
    public function researcherNoAccept($researcherPersonId) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->innerJoin('person p', 'pr.person_id=p.id')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['pr.is_leader' => FALSE, 'pr.deleted' => FALSE, 'pr.person_id' => $researcherPersonId,'pr.acknowledge_status'=> ProjectResearcher::STATUS_PENDING_ACK]);
        return $this->andWhere(['exists', $subQuery]);
    }
        public function researcherList($researcherPersonId) {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->innerJoin('person p', 'pr.person_id=p.id')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['pr.is_leader' => FALSE, 'pr.deleted' => FALSE, 'pr.person_id' => $researcherPersonId]);
        return $this->andWhere(['exists', $subQuery]);
    }
    public function consultantNoAccept($consultantPersonId) {
        $subQuery = (new \yii\db\Query())
                ->select('pc.id')
                ->from('project_consultant pc')
                ->innerJoin('person p', 'pc.person_id=p.id')
                ->andWhere('pc.submission_id = submission.id')
                ->andWhere(['pc.deleted' => FALSE, 'pc.person_id' => $consultantPersonId,'pc.acknowledge_status'=> ProjectConsultant::STATUS_PENDING_ACK]);
        return $this->andWhere(['exists', $subQuery]);
    }
    public function consultantList($consultantPersonId) {
        $subQuery = (new \yii\db\Query())
                ->select('pc.id')
                ->from('project_consultant pc')
                ->innerJoin('person p', 'pc.person_id=p.id')
                ->andWhere('pc.submission_id = submission.id')
                ->andWhere(['pc.deleted' => FALSE, 'pc.person_id' => $consultantPersonId]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function coiPerson($coiPersonId) {
        $subQuery = (new \yii\db\Query())
                ->select('coi.submission_id')
                ->from('submission_coi_person coi')
                ->innerJoin('person p', 'coi.person_id=p.id')
                ->andWhere('coi.submission_id = submission.id')
                ->andWhere(['coi.deleted' => FALSE, 'coi.person_id' => $coiPersonId]);
        return $this->andWhere(['not exists', $subQuery]);
    }

    public function leader($leader) {
        $subQuery = (new \yii\db\Query())
                ->select('project_researcher.id')
                ->from('project_researcher')
                ->andWhere('project_researcher.submission_id = submission.id')
                ->andWhere(['project_researcher.person_id' => $leader, 'project_researcher.is_leader' => TRUE, 'project_researcher.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function isresearch($personId, $projectId) {
        $subQuery = (new \yii\db\Query())
                ->select('project_researcher.id')
                ->from('project_researcher,project')
                ->andWhere('project_researcher.project_id = project.id')
                ->andWhere(['project_researcher.person_id' => $personId, 'project_researcher.project_id' => $projectId, 'project_researcher.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function panel($panel) {
        return $this->andWhere(['project.panel_id' => $panel]);
    }

    public function fda($fda) {
        return $this->andWhere(['project.is_fda' => $fda]);
    }

    public function committee($user, $committeeStatus) {
        return $this->andWhere(['submission_committee.person_id' => $user])->andWhere(['submission_committee.status' => $committeeStatus])->andWhere(['submission_committee.deleted' => FALSE]);
    }

    public function secretary($user) {
        return $this->andWhere(['submission.secretary_person' => $user]);
    }

    public function project($projectId) {
        return $this->andWhere(['submission.project_id' => $projectId]);
    }

    public function refSubmission($refSubmission) {
        return $this->andWhere(['submission.ref_submission_id' => $refSubmission]);
    }

    public function statusDay($day) {
        $subQuery = (new \yii\db\Query())
                ->select(["DATE_ADD(DATE(`ssh`.`created_at`), INTERVAL {$day} DAY)"])
                ->from('submission_status_history ssh')
                ->where('ssh.submission_id = submission.id')
                ->orderBy('ssh.id DESC')
                ->limit(1);
        return $this->andWhere(['=', 'CURDATE()', $subQuery]);
    }

    public function overDay($day) {
        $subQuery = (new \yii\db\Query())
                ->select(["DATE_ADD(DATE(`ssh`.`created_at`), INTERVAL {$day} DAY)"])
                ->from('submission_status_history ssh')
                ->where('ssh.submission_id = submission.id')
                ->andWhere(['ssh.status' => Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT])
                ->orderBy('ssh.id DESC')
                ->limit(1);
        return $this->andWhere(['=', 'CURDATE()', $subQuery]);
    }

    public function committeeRepick() {
        $acceptQuery = (new \yii\db\Query())
                ->select(["COUNT(*)"])
                ->from('submission_committee sc')
                ->where('sc.submission_id = submission.id')
                ->andWhere(['sc.deleted' => FALSE])
                ->andWhere(['sc.status' => SubmissionCommittee::STATUS_ACCEPTED]);

        $rejectQuery = (new \yii\db\Query())
                ->select(["sc.id"])
                ->from('submission_committee sc')
                ->where('sc.submission_id = submission.id')
                ->andWhere(['sc.deleted' => FALSE])
                ->andWhere(['sc.status' => SubmissionCommittee::STATUS_REJECTED])
                ->orderBy('sc.id DESC')
                ->limit(1);

        $uncheckQuery = (new \yii\db\Query())
                ->select(["COUNT(*)"])
                ->from('submission_committee sc')
                ->where('sc.submission_id = submission.id')
                ->andWhere(['sc.deleted' => FALSE])
                ->andWhere(['sc.status' => NULL])
                ->orderBy('sc.id DESC')
                ->limit(1);
//        \yii\helpers\VarDumper::dump($acceptQuery->createCommand()->rawSql);
        return $this->andWhere("({$acceptQuery->createCommand()->rawSql}) < submission_type.committee_count")
                        ->andWhere(['exists', $rejectQuery])->andWhere(['not exists', $uncheckQuery]);
    }

    public function noResubmit() {
        $subQuery = (new \yii\db\Query())
                ->select(["id"])
                ->from('submission rs')
                ->where('rs.ref_submission_id = submission.id')
                ->andwhere('rs.id > submission.id')
                ->andwhere(['rs.deleted' => FALSE])
                ->limit(1);
        return $this->andWhere(['not exists', $subQuery]);
    }

    public function hasPendingSubmission() {
        $subQuery = (new \yii\db\Query())
                ->select(["id"])
                ->from('submission rs')
                ->where('rs.ref_submission_id = submission.id')
                ->andwhere('rs.id > submission.id')
//                ->andwhere('rs.status = 100')
                ->andWhere(['or', ['=', 'rs.status', 100], ['=', 'rs.status', 190], ['=', 'rs.status', 150]])
                ->andwhere(['rs.deleted' => FALSE])
                ->limit(1);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function isRef() {
        return $this->andWhere(['not', ['submission.ref_submission_id' => null]]);
    }

    public function noRef() {
        return $this->andWhere(['submission.ref_submission_id' => null]);
    }

    public function hasPendingResearcher() {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['pr.deleted' => 0, 'pr.acknowledge_status' => ProjectResearcher::STATUS_PENDING_ACK]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function dateStatusBetween($startDate, $endDate, $status) {
        $d = new \DateTime($startDate);
        $subQuery = (new \yii\db\Query())
                ->select('sh.id')
                ->from('submission_status_history sh')
                ->andWhere('sh.submission_id = submission.id')
                ->andWhere(['sh.status' => $status]);
        if ($d !== FALSE) {
            $subQuery->andWhere(['>=', 'sh.created_at', $d->format('Y-m-d')]);
        }
        $d = new \DateTime($endDate);
        if ($d !== FALSE) {
            $d->add(new \DateInterval("P1D"));
            $subQuery->andWhere(['<', 'sh.created_at', $d->format('Y-m-d')]);
        }
        return $this->andWhere(['exists', $subQuery]);
    }

    public function yearMonthStatus($year, $month, $status) {
        $subQuery = (new \yii\db\Query())
                ->select('sh.id')
                ->from('submission_status_history sh')
                ->andWhere('sh.submission_id = submission.id')
                ->andWhere(['YEAR(sh.created_at)' => $year])
                ->andWhere(['MONTH(sh.created_at)' => $month])
                ->andWhere(['sh.status' => $status]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function yearStatus($year, $status) {
        $subQuery = (new \yii\db\Query())
                ->select('sh.id')
                ->from('submission_status_history sh')
                ->andWhere('sh.submission_id = submission.id')
                ->andWhere(['YEAR(sh.created_at)' => $year])
                ->andWhere(['sh.status' => $status]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function dateStatus($startDate, $endDate, $status) {
        $d = new \DateTime($startDate);

        $subQuery = (new \yii\db\Query())
                ->select('sh.id')
                ->from('submission_status_history sh')
                ->andWhere('sh.submission_id = submission.id')
                ->andWhere(['submission.deleted' => 0])
                ->andWhere(['sh.status' => $status]);
        if ($d !== FALSE) {
            $subQuery->andWhere(['>=', 'sh.created_at', $d->format('Y-m-d')]);
        }
        $d = new \DateTime($endDate);
        if ($d !== FALSE) {
            $d->add(new \DateInterval("P1D"));
            $subQuery->andWhere(['<', 'sh.created_at', $d->format('Y-m-d')]);
        }
        return $this->andWhere(['exists', $subQuery]);
    }

    public function hasLeader() {
        $subQuery = (new \yii\db\Query())
                ->select('pr.id')
                ->from('project_researcher pr')
                ->andWhere('pr.submission_id = submission.id')
                ->andWhere(['pr.deleted' => 0, 'pr.is_leader' => 1]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function hasCertifiedDate($has = true) {
        if ($has) {
            return $this->andWhere(['not', ['submission.certified_date' => null]]);
        } else {
            return $this->andWhere(['submission.certified_date' => null]);
        }
    }

    public function notId($id) {
        return $this->andWhere(['not', ['submission.id' => $id]]);
    }

}
