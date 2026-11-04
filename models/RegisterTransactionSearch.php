<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RegisterTransaction;

/**
 * RegisterTransactionSearch represents the model behind the search form about `app\models\RegisterTransaction`.
 */
class RegisterTransactionSearch extends RegisterTransaction
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'meeting_id', 'person_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['invited_at', 'registered_at', 'out_at', 'created_at', 'updated_at'], 'safe'],
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
        $query = RegisterTransaction::find();

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
            'meeting_id' => $this->meeting_id,
            'person_id' => $this->person_id,
            'invited_at' => $this->invited_at,
            'registered_at' => $this->registered_at,
            'out_at' => $this->out_at,
            'deleted' => $this->deleted,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
