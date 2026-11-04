<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Position;

/**
 * PositionSearch represents the model behind the search form about `app\models\Position`.
 */
class PositionSearch extends Position
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at', 'name_eng','createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = Position::find();
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
            'asc' => ['CONVERT(position.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(position.name USING TIS620)' => SORT_DESC],
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
            'position.id' => $this->id,
            'position.deleted' => $this->deleted,
            'position.created_by' => $this->created_by,
            'position.created_at' => $this->created_at,
            'position.updated_by' => $this->updated_by,
            'position.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'position.name', $this->name])
            ->andFilterWhere(['like', 'position.name_eng', $this->name_eng]);

        return $dataProvider;
    }
}
