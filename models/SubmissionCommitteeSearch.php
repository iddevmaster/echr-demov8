<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionCommittee;

/**
 * SubmissionCommitteeSearch represents the model behind the search form about `app\models\SubmissionCommittee`.
 */
class SubmissionCommitteeSearch extends SubmissionCommittee {

    const SCENARIO_COMMITTEE_ASSESSMENT_StATISTIC = 'SCENARIO_COMMITTEE_ASSESSMENT_StATISTIC';
    public $meetingId, $personDeleted, $startMeetingDate, $endMeetingDate;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['person_id', 'startMeetingDate', 'endMeetingDate'], 'required', 'on' => self::SCENARIO_COMMITTEE_ASSESSMENT_StATISTIC],
            [['id', 'person_id', 'project_id', 'submission_id', 'deleted', 'created_by', 'updated_by', 'meetingId', 'personDeleted'], 'integer'],
            [['status', 'submit_date', 'return_date', 'remark', 'created_at', 'updated_at', 'startMeetingDate', 'endMeetingDate'], 'safe'],
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
        $query = SubmissionCommittee::find();
        $query->joinWith(['person']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'submission_committee.id' => $this->id,
            'submission_committee.person_id' => $this->person_id,
            'submission_committee.project_id' => $this->project_id,
            'submission_committee.submission_id' => $this->submission_id,
            'submission_committee.submit_date' => $this->submit_date,
            'submission_committee.return_date' => $this->return_date,
            'submission_committee.deleted' => $this->deleted,
            'submission_committee.created_by' => $this->created_by,
            'submission_committee.created_at' => $this->created_at,
            'submission_committee.updated_by' => $this->updated_by,
            'submission_committee.updated_at' => $this->updated_at,
            'person.deleted' => $this->personDeleted,
        ]);

        $query->andFilterWhere(['like', 'submission_committee.status', $this->status])
                ->andFilterWhere(['like', 'submission_committee.remark', $this->remark]);

        if (!empty($this->startMeetingDate) && !empty($this->endMeetingDate)) {
            $query->betweenMeetingDate($this->startMeetingDate, $this->endMeetingDate);
        }
        
        return $dataProvider;
    }

}
