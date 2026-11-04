<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionVolunteer;

/**
 * SubmissionVolunteerSearch represents the model behind the search form about `app\models\SubmissionVolunteer`.
 */
class SubmissionVolunteerSearch extends SubmissionVolunteer {

    public $projectId;
    public $submissionId;
    public $notId;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'submission_id', 'volunteer_id', 'type', 'follow_up_no', 'created_by', 'updated_by', 'projectId', 'submissionId', 'notId'], 'integer'],
            [['deleted', 'created_at', 'updated_at'], 'safe'],
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
        $query = SubmissionVolunteer::find();

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
            'submission_volunteer.id' => $this->id,
            'submission_volunteer.submission_id' => $this->submission_id,
            'submission_volunteer.volunteer_id' => $this->volunteer_id,
            'submission_volunteer.type' => $this->type,
            'submission_volunteer.follow_up_no' => $this->follow_up_no,
            'submission_volunteer.created_by' => $this->created_by,
            'submission_volunteer.created_at' => $this->created_at,
            'submission_volunteer.updated_by' => $this->updated_by,
            'submission_volunteer.updated_at' => $this->updated_at,
        ]);
        
        if (!empty($this->notId)) {
            $query->notId($this->notId);
        }

        $query->andFilterWhere(['like', 'submission_volunteer.deleted', $this->deleted]);
        if (!empty($this->projectId)) {
            $query->joinWith(['volunteer'])->andFilterWhere(['volunteer.project_id' => $this->projectId]);
            if (!empty($this->submissionId)) {
                $query->andWhere(['or', ['submission_volunteer.submission_id' => $this->submissionId], ['submission_volunteer.submission_id' => null]]);
            } else {
                $query->andWhere(['submission_volunteer.submission_id' => null]);
            }
        }

        return $dataProvider;
    }

}
