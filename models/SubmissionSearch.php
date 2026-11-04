<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Submission;

/**
 * SubmissionSearch represents the model behind the search form about `app\models\Submission`.
 */
class SubmissionSearch extends Submission {

    const SCENARIO_SUMMARY_DURATION = 'summary-duration';
    const SCENARIO_SUBMISSION_STATISTICS = 'submission-statistics';

    public $statusYear, $startYear, $endYear, $statusEdit, $resolutionRe;
    public $statusMonth;

    /**
     * @inheritdoc
     */
    public $consultantPersonId, $name, $researcherPersonId, $is_leader, $panel_id
            , $submission_type_group_id, $committeeId, $committeeStatus, $consideration
            , $personName, $noResubmit, $coiPersonId, $hasProjectCode, $endDate
            , $startDate, $noRef, $fda, $accept, $researcherOrg, $researcherDep, $researcherDiv, $groupByProject, $isOngoing, $sae, $onlySubmitted, $secretary;

    public function rules() {
        return [
            [['startDate', 'endDate', 'submission_type_group_id', 'panel_id'], 'required', 'on' => self::SCENARIO_SUMMARY_DURATION],
            [['startDate', 'endDate', 'agendaId', 'panel_id'], 'required', 'on' => self::SCENARIO_SUBMISSION_STATISTICS],
            [['id', 'project_id', 'organization_id', 'deleted', 'created_by', 'updated_by', 'submission_type_id', 'researcherPersonId', 'consultantPersonId', 'committeeId', 'committeeStatus', 'consideration', 'noResubmit', 'coiPersonId', 'project_coordinator_id', 'project_coordinator_2nd_id', 'project_coordinator_3rd_id', 'project_viewer_id', 'is_legacy', 'hasProjectCode', 'statusYear', 'statusMonth', 'fda', 'panel_id', 'researcherOrg', 'researcherDep', 'researcherDiv', 'groupByProject', 'isOngoing', 'sae'], 'integer'],
            [['remark', 'certified_date', 'status', 'full_doc_file', 'created_at', 'updated_at', 'created_by', 'name', 'is_leader', 'submission_type_group_id', 'responsible_person', 'personName', 'endDate', 'startDate', 'noRef', 'accept', 'onlySubmitted', 'startYear', 'endYear', 'secretary', 'statusEdit', 'resolutionRe'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $currentRole = \Yii::$app->session->get('currentRole');

        $query = Submission::find();
        $join = ['project', 'submissionType', 'submissionType.submissionTypeGroup'];
//        if ($currentRole['role_id'] == Role::RESEARCHER) {
//            $join[] = 'project.projectResearchers';
//        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
            $join[] = 'submissionCommittees';
        }
        $query->joinWith($join);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => ['defaultOrder' => ['project_code' => SORT_DESC]]
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
//        /$dataProvider->sort = ['defaultOrder' => ['project.project_code' => SORT_DESC,]];

        $query->andFilterWhere([
            'submission.id' => $this->id,
            'submission.certified_date' => $this->certified_date,
            'submission.project_id' => $this->project_id,
            'submission.organization_id' => $this->organization_id,
            'submission.deleted' => $this->deleted,
            'submission.created_by' => $this->created_by,
            'submission.created_at' => $this->created_at,
            'submission.updated_by' => $this->updated_by,
            'submission.updated_at' => $this->updated_at,
//            'submission.project_coordinator_id' => $this->project_coordinator_id,
//            'submission.project_coordinator_2nd_id' => $this->project_coordinator_2nd_id,
//            'submission.project_coordinator_3rd_id' => $this->project_coordinator_3rd_id,
            'submission.project_viewer_id' => $this->project_viewer_id,
            'submission.submission_type_id' => $this->submission_type_id,
            'submission.remark' => $this->remark,
            //'submission.status' => $this->status,
            'submission.resolution' => $this->resolution,
            'submission.is_legacy' => $this->is_legacy,
//            'project_researcher.person_id' => $this->researcherPersonId,
//            'project_researcher.is_leader' => $this->is_leader,
            'project.panel_id' => $this->panel_id,
            'project.is_fda' => $this->fda,
            'project.is_closed' => $this->isOngoing,
            'submission_type.submission_type_group_id' => $this->submission_type_group_id,
//            'submission.responsible_person' => $this->responsible_person,
            'submission_committee.person_id' => $this->committeeId,
//            'submission_coi_person.person_id'=> $this->coiPersonId,
            'submission_committee.status' => $this->committeeStatus,
//            'submission_committee.deleted'=> $this->deleted,
            'submission_type.meeting_consideration' => $this->consideration,
        ]);
//        $query->andWhere(['not like', 'submission_coi_person.person_id', $this->coiPersonId]);
        if (isset($this->sae)) {
            $query->andFilterWhere(['or', ['submission.submission_type_id' => SubmissionType::TYPE_EXTERNAL_SAE], ['submission.submission_type_id' => SubmissionType::TYPE_INTERNAL_SAE]]);
        }
        $query->andFilterWhere(['or', ['like', 'project.name_thai', $this->name], ['like', 'project.name_eng', $this->name], ['like', 'project.project_code', $this->name]]);
//        $query->andFilterWhere(['or',['like', 'CONCAT(person.first_name, person.last_name)', $this->personName]]);
        if (isset($this->project_coordinator_id)) {
            $query->andFilterWhere(['or', ['=', 'submission.project_coordinator_id', $this->project_coordinator_id], ['=', 'submission.project_coordinator_2nd_id', $this->project_coordinator_id], ['=', 'submission.project_coordinator_3rd_id', $this->project_coordinator_id]]);
        }
        if ($this->statusEdit == 'Edit') {
            $query->andWhere(['or', ['submission.status' => Submission::STATUS_DOC_REJECTED], ['submission.status' => Submission::STATUS_DOC_REJECTED_BY_COMMITTEE]]);
        }
        if ($this->resolutionRe == 'Resubmission') {
            $query->andWhere(['or', ['submission.resolution' => Submission::RESOLUTION_C], ['submission.resolution' => Submission::RESOLUTION_R]]);
        }
        if (!empty($this->personName)) {
            $query->researcherName($this->personName);
        }
        if (!empty($this->consultantPersonId)) {
            $query->consultantList($this->consultantPersonId);
        }
        if (!empty($this->researcherPersonId)) {
            $query->researcherList($this->researcherPersonId);
        }
        if (!empty($this->coiPersonId)) {
            $query->coiPerson($this->coiPersonId);
        }
        if (!empty($this->is_leader)) {
            $query->leader($this->is_leader);
        }
//        if (!empty($this->is_legacy)) {
//            $query->andFilterWhere(['submission.is_legacy' => $this->is_legacy]);
//        }
        if ($this->noResubmit) {
            $query->noResubmit();
        }
        if ($this->noRef) {
            $query->noRef();
        }
        $query->andFilterWhere(['like', 'submission.full_doc_file', $this->full_doc_file]);
        //$query->groupBy('submission.status');
        $customValue = Submission::getCustomStatusValue($this->status);
        if (isset($customValue)) {
            $query->andWhere(['>=', 'submission.status', $customValue['min']])->andWhere(['<', 'submission.status', $customValue['max']]);
        } elseif (isset($this->status) && ($currentRole['role_id'] != Role::COMMITTEE)) {
            $query->andFilterWhere(['submission.status' => $this->status]);
        }

//        if ($currentRole['role_id'] == Role::SECRETARY) {
//            $query->andWhere(['>=', 'submission.status', Submission::STATUS_SECRETARY_SELECTED])->andWhere(['<=', 'submission.status', Submission::STATUS_COMMITTEE_SELECTED]);
//        }
        if ($currentRole['role_id'] == Role::SECRETARY && isset($this->secretary)) {
//            $query->andWhere(['>=', 'submission.status', Submission::STATUS_SECRETARY_SELECTED])->andWhere(['<=', 'submission.status', Submission::STATUS_COMMITTEE_SELECTED]);
            $query->andFilterWhere(['like', 'submission.status', $this->status]);
            $query->andFilterWhere(['like', 'submission.secretary_person', \Yii::$app->user->identity->id]);
        }
//        $customCommitteeValue = Submission::getCustomStatusCommitteeValues($this->status);
//        if (isset($customCommitteeValue)) {
//            $query->andWhere(['>=', 'submission.status', $customValue['min']])->andWhere(['<', 'submission.status', $customValue['max']]);
//        }
        if ($currentRole['role_id'] == Role::COMMITTEE) {
//            if (isset($this->status)) {
//                $query->andFilterWhere(['submission.status' => $this->status]);
//            } else {
//                $query->andWhere(['>=', 'submission.status', Submission::STATUS_COMMITTEE_SELECTED])->andWhere(['<=', 'submission.status', Submission::STATUS_MEETING_DONE]);
//            }
            $query->andFilterWhere(['like', 'submission_committee.deleted', $this->deleted]);
        }
        if (isset($this->responsible_person)) {
            if ($currentRole['role_id'] == Role::STAFF) {
                if ($this->responsible_person == -1) {
                    $query->andWhere(['submission.responsible_person' => NULL]);
                } else {
                    $query->andWhere(['submission.responsible_person' => $this->responsible_person]);
                }
            }

//            elseif ($currentRole['role_id'] == Role::ADMIN) {
//                if ($this->responsible_person == -1) {
//                    $query->andWhere(['submission.responsible_person' => NULL]);
//                }
//            }
        }
        if (isset($this->hasProjectCode)) {
            $query->andWhere(['project.project_code' => NULL]);
        }

        if (!empty($this->agendaId)) {
            $query->hasMeetingAgendaPanelTotal($this->agendaId);
        }

        if ($this->scenario == SubmissionSearch::SCENARIO_SUBMISSION_STATISTICS) {
            $query->dateStatusBetween($this->startDate, $this->endDate, Submission::STATUS_CODE_GENERATED);
        } else {
            if (!empty($this->startDate) && !empty($this->endDate) && !empty($this->status)) {
                $query->dateStatusBetween($this->startDate, $this->endDate, $this->status);
            }
        }
        if (isset($this->accept)) {
            if ($this->accept == 1) {
                $query->researcherNoAccept(\Yii::$app->user->identity->person->id);
            } elseif ($this->accept == 2) {
                $query->consultantNoAccept(\Yii::$app->user->identity->person->id);
            }
        }
        if (isset($this->groupByProject)) {
            $query->groupBy('submission.project_id');
        }
        if (isset($this->onlySubmitted)) {
            $query->onlySubmitted($this->onlySubmitted);
        }
        return $dataProvider;
    }

}
