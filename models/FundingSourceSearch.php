<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FundingSource;

/**
 * FundingSourceSearch represents the model behind the search form about `app\models\FundingSource`.
 */
class FundingSourceSearch extends FundingSource {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['name', 'created_at', 'updated_at', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = FundingSource::find();
        $query->joinWith(['createdByUserProfile createdByUserProfile', 'updatedByUserProfile updatedByUserProfile']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            $query->where('0=1');
            return $dataProvider;
        }
        $dataProvider->sort->attributes['name'] = [
            'asc' => ['CONVERT(funding_source.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(funding_source.name USING TIS620)' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['createdByUserProfile.fullName'] = [
            'asc' => ['CONVERT(CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name) USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name) USING TIS620)' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['updatedByUserProfile.fullName'] = [
            'asc' => ['CONVERT(CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name) USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name) USING TIS620)' => SORT_DESC],
        ];
        $query->andFilterWhere([
            'funding_source.id' => $this->id,
            'funding_source.deleted' => $this->deleted,
            'funding_source.created_by' => $this->created_by,
            'funding_source.created_at' => $this->created_at,
            'funding_source.updated_by' => $this->updated_by,
            'funding_source.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'funding_source.name', $this->name]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);

        return $dataProvider;
    }

}
