<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectConsultant;

/**
 * ProjectConsultantSearch represents the model behind the search form about `app\models\ProjectConsultant`.
 */
class ProjectConsultantSearch extends ProjectConsultant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'person_id', 'project_id', 'submission_id', 'acknowledge_status', 'acknowledge_by', 'created_by', 'updated_by'], 'integer'],
            [['mail_sent', 'mail_sent_at', 'acknowledge_at', 'deleted', 'created_at', 'updated_at'], 'safe'],
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
        $query = ProjectConsultant::find();

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
            'project_consultant.id' => $this->id,
            'project_consultant.person_id' => $this->person_id,
            'project_consultant.project_id' => $this->project_id,
            'project_consultant.submission_id' => $this->submission_id,
            'project_consultant.mail_sent_at' => $this->mail_sent_at,
            'project_consultant.acknowledge_status' => $this->acknowledge_status,
            'project_consultant.acknowledge_by' => $this->acknowledge_by,
            'project_consultant.acknowledge_at' => $this->acknowledge_at,
            'project_consultant.created_by' => $this->created_by,
            'project_consultant.created_at' => $this->created_at,
            'project_consultant.updated_by' => $this->updated_by,
            'project_consultant.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'project_consultant.mail_sent', $this->mail_sent])
            ->andFilterWhere(['like', 'project_consultant.deleted', $this->deleted]);

        return $dataProvider;
    }
}
