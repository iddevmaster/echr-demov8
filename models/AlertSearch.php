<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Alert;

/**
 * AlertSearch represents the model behind the search form about `app\models\Alert`.
 */
class AlertSearch extends Alert
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'is_acknowledged', 'acknowledged_by', 'user_id', 'submission_id'], 'integer'],
            [['message', 'acknowledged_at', 'alerted_at', 'created_at'], 'safe'],
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
        $query = Alert::find()->orderBy('alert.created_at DESC');

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
            'type' => $this->type,
            'is_acknowledged' => $this->is_acknowledged,
            'acknowledged_by' => $this->acknowledged_by,
            'acknowledged_at' => $this->acknowledged_at,
            'alerted_at' => $this->alerted_at,
            'created_at' => $this->created_at,
            'user_id' => $this->user_id,
            'submission_id' => $this->submission_id,
        ]);

        $query->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
