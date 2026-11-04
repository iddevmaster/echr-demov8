<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Meeting;

/**
 * MeetingSearch represents the model behind the search form about `app\models\Meeting`.
 */
class MeetingSearch extends Meeting {

    const SCENARIO_API_SEARCH = 'api-search';
    /**
     * @inheritdoc
     */
    public $start, $end, $Idperson;

    public function rules() {
        return [
            [['id', 'department_id', 'panel_id', 'submission_id', 'organization_id', 'meeting_no', 'year', 'deleted', 'created_by', 'updated_by', 'Idperson', 'checked_status', 'checked_staff', 'checked_secretary_first', 'checked_secretary_second'], 'integer'],
            [['title', 'start_date', 'end_date', 'start_time', 'end_time', 'status', 'is_public', 'created_at', 'updated_at', 'start', 'end', 'Idperson', 'staff_checked_at', 'sec1_checked_at', 'sec2_checked_at'], 'safe'],
            [['start', 'end'], 'required', 'on' => self::SCENARIO_API_SEARCH],
            [['start', 'end'], 'date', 'format' => 'php:Y-m-d', 'on' => self::SCENARIO_API_SEARCH],
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
        $query = Meeting::find();

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
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'department_id' => $this->department_id,
            'panel_id' => $this->panel_id,
            'submission_id' => $this->submission_id,
            'organization_id' => $this->organization_id,
            'meeting_no' => $this->meeting_no,
            'year' => $this->year,
            'deleted' => $this->deleted,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'checked_status' => $this->checked_status,
            'checked_staff' => $this->checked_staff,
            'checked_secretary_first' => $this->checked_secretary_first,
            'checked_secretary_second' => $this->checked_secretary_second,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'is_public', $this->is_public]);

        if (!empty($this->start)) {
            $d = new \DateTime($this->start);
            if ($d !== FALSE) {
                $query->andFilterWhere(['>=', 'meeting.start_date', $d->format('Y-m-d')]);
            }
        }
        if (!empty($this->end)) {
            $d = new \DateTime($this->end);
            if ($d !== FALSE) {
                $d->add(new \DateInterval("P1D"));
                $query->andFilterWhere(['<', 'meeting.start_date', $d->format('Y-m-d')]);
            }
        }
        if (!empty($this->Idperson)) {
            $query->person($this->Idperson);
        }
        return $dataProvider;
    }

}
