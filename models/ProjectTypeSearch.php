<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectType;

/**
 * ProjectTypeSearch represents the model behind the search form about `app\models\ProjectType`.
 */
class ProjectTypeSearch extends ProjectType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'min_occur', 'created_by', 'updated_by'], 'integer'],
            [['name', 'is_alert', 'deleted', 'created_at', 'updated_at'], 'safe'],
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
        $query = ProjectType::find();

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
            'min_occur' => $this->min_occur,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'is_alert', $this->is_alert])
            ->andFilterWhere(['like', 'deleted', $this->deleted]);

        return $dataProvider;
    }
}
