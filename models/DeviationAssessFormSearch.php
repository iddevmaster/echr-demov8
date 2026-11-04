<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DeviationAssessForm;

/**
 * DeviationAssessFormSearch represents the model behind the search form about `app\models\DeviationAssessForm`.
 */
class DeviationAssessFormSearch extends DeviationAssessForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'submission_id', 'submission_committee_id', 'review_choice_id', 'resolution_id', 'created_by', 'updated_by'], 'integer'],
            [['review_choice_text', 'suggestion', 'deleted', 'created_at', 'updated_at'], 'safe'],
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
        $query = DeviationAssessForm::find();

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
            'submission_id' => $this->submission_id,
            'submission_committee_id' => $this->submission_committee_id,
            'review_choice_id' => $this->review_choice_id,
            'resolution_id' => $this->resolution_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'review_choice_text', $this->review_choice_text])
            ->andFilterWhere(['like', 'suggestion', $this->suggestion])
            ->andFilterWhere(['like', 'deleted', $this->deleted]);

        return $dataProvider;
    }
}
