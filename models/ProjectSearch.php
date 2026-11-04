<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Project;

/**
 * ProjectSearch represents the model behind the search form about `app\models\Project`.
 */
class ProjectSearch extends Project {

    public $expire_at1, $expire_at2, $next_progress_at1, $next_progress_at2;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id', 'funding_source_id', 'panel_id', 'organization_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['name_thai', 'name_eng', 'start_date', 'end_date', 'funding_source_description', 'is_child_project', 'progress_period', 'remark', 'certified_date', 'status', 'project_code', 'created_at', 'updated_at', 'expire_at1', 'expire_at2', 'next_progress_at1', 'next_progress_at2'], 'safe'],
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
        $query = Project::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'project.id' => $this->id,
            'project.start_date' => $this->start_date,
            'project.end_date' => $this->end_date,
            'project.funding_source_id' => $this->funding_source_id,
            'project.certified_date' => $this->certified_date,
            'project.panel_id' => $this->panel_id,
            'project.organization_id' => $this->organization_id,
            'project.deleted' => $this->deleted,
            'project.created_by' => $this->created_by,
            'project.created_at' => $this->created_at,
            'project.updated_by' => $this->updated_by,
            'project.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'project.name_thai', $this->name_thai])
                ->andFilterWhere(['like', 'project.name_eng', $this->name_eng])
                ->andFilterWhere(['like', 'project.funding_source_description', $this->funding_source_description])
                ->andFilterWhere(['like', 'project.is_child_project', $this->is_child_project])
                ->andFilterWhere(['like', 'project.progress_period', $this->progress_period])
                ->andFilterWhere(['like', 'project.remark', $this->remark])
                ->andFilterWhere(['like', 'project.status', $this->status])
                ->andFilterWhere(['like', 'project.project_code', $this->project_code]);
        
        if (!empty($this->expire_at1)) {
            $d = new \DateTime($this->expire_at1);
            if ($d !== FALSE) {
                $query->andFilterWhere(['>=', 'project.expire_at', $d->format('Y-m-d')]);
            }
        }
        if (!empty($this->expire_at2)) {
            $d = new \DateTime($this->expire_at2);
            if ($d !== FALSE) {
                $d->add(new \DateInterval("P1D"));
                $query->andFilterWhere(['<', 'project.expire_at', $d->format('Y-m-d')]);
            }
        }
        if (!empty($this->next_progress_at1)) {
            $d = new \DateTime($this->next_progress_at1);
            if ($d !== FALSE) {
                $query->andFilterWhere(['>=', 'project.next_progress_at', $d->format('Y-m-d')]);
            }
        }
        if (!empty($this->next_progress_at2)) {
            $d = new \DateTime($this->next_progress_at2);
            if ($d !== FALSE) {
                $d->add(new \DateInterval("P1D"));
                $query->andFilterWhere(['<', 'project.next_progress_at', $d->format('Y-m-d')]);
            }
        }
        return $dataProvider;
    }

}
