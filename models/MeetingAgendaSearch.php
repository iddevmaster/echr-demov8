<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MeetingAgenda;

/**
 * MeetingAgendaSearch represents the model behind the search form about `app\models\MeetingAgenda`.
 */
class MeetingAgendaSearch extends MeetingAgenda
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'meeting_id', 'project_id', 'submission_id', 'parent_id', 'approved_by', 'deleted', 'created_by', 'updated_by', 'agenda_id'], 'integer'],
            [['title', 'description', 'conclusion', 'summary', 'sort_label', 'approved_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = MeetingAgenda::find();

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
            'meeting_id' => $this->meeting_id,
            'project_id' => $this->project_id,
            'submission_id' => $this->submission_id,
            'parent_id' => $this->parent_id,
            'approved_at' => $this->approved_at,
            'approved_by' => $this->approved_by,
            'deleted' => $this->deleted,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
            'agenda_id' => $this->agenda_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'conclusion', $this->conclusion])
            ->andFilterWhere(['like', 'summary', $this->summary])
            ->andFilterWhere(['like', 'sort_label', $this->sort_label]);

        return $dataProvider;
    }
}
