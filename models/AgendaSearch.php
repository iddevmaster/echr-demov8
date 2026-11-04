<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Agenda;

/**
 * AgendaSearch represents the model behind the search form about `app\models\Agenda`.
 */
class AgendaSearch extends Agenda {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id', 'submission_type_id', 'deleted', 'created_by', 'updated_by','is_submission'], 'integer'],
                [['name', 'label', 'created_at', 'updated_at','parent_id'], 'safe'],
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
        $query = Agenda::find();

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
            'agenda.id' => $this->id,
            'agenda.submission_type_id' => $this->submission_type_id,
            'agenda.deleted' => $this->deleted,
            'agenda.created_by' => $this->created_by,
            'agenda.created_at' => $this->created_at,
            'agenda.updated_by' => $this->updated_by,
            'agenda.updated_at' => $this->updated_at,
            'agenda.is_submission' => $this->is_submission,
        ]);

        $query->andFilterWhere(['like', 'agenda.name', $this->name])
                ->andFilterWhere(['like', 'agenda.label', $this->label]);


        return $dataProvider;
    }

}
