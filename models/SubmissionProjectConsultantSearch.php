<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionProjectConsultant;

/**
 * SubmissionProjectConsultantSearch represents the model behind the search form about `app\models\SubmissionProjectConsultant`.
 */
class SubmissionProjectConsultantSearch extends SubmissionProjectConsultant
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'project_consultant_id', 'submission_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['remark', 'deleted', 'created_at', 'updated_at'], 'safe'],
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
        $query = SubmissionProjectConsultant::find();

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
            'project_consultant_id' => $this->project_consultant_id,
            'submission_id' => $this->submission_id,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'deleted', $this->deleted]);

        return $dataProvider;
    }
}
