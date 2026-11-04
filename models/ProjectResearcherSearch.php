<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectResearcher;

/**
 * ProjectResearcherSearch represents the model behind the search form about `app\models\ProjectResearcher`.
 */
class ProjectResearcherSearch extends ProjectResearcher {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'person_id', 'project_id', 'deleted', 'created_by', 'updated_by', 'submission_id'], 'integer'],
            [['is_leader', 'created_at', 'updated_at'], 'safe'],
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
        $query = ProjectResearcher::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'project_researcher.id' => $this->id,
            'project_researcher.person_id' => $this->person_id,
            'project_researcher.project_id' => $this->project_id,
            'project_researcher.submission_id' => $this->submission_id,
            'project_researcher.deleted' => $this->deleted,
            'project_researcher.created_by' => $this->created_by,
            'project_researcher.created_at' => $this->created_at,
            'project_researcher.updated_by' => $this->updated_by,
            'project_researcher.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'project_researcher.is_leader', $this->is_leader]);

        return $dataProvider;
    }

}
