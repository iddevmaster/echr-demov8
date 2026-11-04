<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionVolunteerNumber;

/**
 * SubmissionVolunteerNumberSearch represents the model behind the search form about `app\models\SubmissionVolunteerNumber`.
 */
class SubmissionVolunteerNumberSearch extends SubmissionVolunteerNumber
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'value', 'volunteer_number_id', 'submission_id', 'project_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = SubmissionVolunteerNumber::find();

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
            'value' => $this->value,
            'volunteer_number_id' => $this->volunteer_number_id,
            'submission_id' => $this->submission_id,
            'project_id' => $this->project_id,
            'deleted' => $this->deleted,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
