<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SaeAssessForm;

/**
 * SaeAssessFormSearch represents the model behind the search form about `app\models\SaeAssessForm`.
 */
class SaeAssessFormSearch extends SaeAssessForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'submission_id', 'submission_committee_id', 'sae_total', 'sae_for', 'sae_for_fatal', 'sae_dom', 'sae_dom_fatal', 'ec', 'ec_fatal', 'ec_cure', 'ec_not_cure', 'ec_unknown_cure', 'ec_drug', 'ec_not_drug', 'ec_unknown_drug', 'resolution_id', 'created_by', 'updated_by'], 'integer'],
            [['suggestion', 'condition', 'addition', 'deleted', 'created_at', 'updated_at'], 'safe'],
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
        $query = SaeAssessForm::find();

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
            'sae_total' => $this->sae_total,
            'sae_for' => $this->sae_for,
            'sae_for_fatal' => $this->sae_for_fatal,
            'sae_dom' => $this->sae_dom,
            'sae_dom_fatal' => $this->sae_dom_fatal,
            'ec' => $this->ec,
            'ec_fatal' => $this->ec_fatal,
            'ec_cure' => $this->ec_cure,
            'ec_not_cure' => $this->ec_not_cure,
            'ec_unknown_cure' => $this->ec_unknown_cure,
            'ec_drug' => $this->ec_drug,
            'ec_not_drug' => $this->ec_not_drug,
            'ec_unknown_drug' => $this->ec_unknown_drug,
            'resolution_id' => $this->resolution_id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'suggestion', $this->suggestion])
            ->andFilterWhere(['like', 'condition', $this->condition])
            ->andFilterWhere(['like', 'addition', $this->addition])
            ->andFilterWhere(['like', 'deleted', $this->deleted]);

        return $dataProvider;
    }
}
