<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MeetingPerson;

/**
 * MeetingPersonSearch represents the model behind the search form about `app\models\MeetingPerson`.
 */
class MeetingPersonSearch extends MeetingPerson {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'meeting_id', 'person_id', 'deleted', 'created_by', 'updated_by', 'role_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = MeetingPerson::find();

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
            'meeting_person.id' => $this->id,
            'meeting_person.meeting_id' => $this->meeting_id,
            'meeting_person.person_id' => $this->person_id,
            'meeting_person.deleted' => $this->deleted,
            'meeting_person.created_by' => $this->created_by,
            'meeting_person.created_at' => $this->created_at,
            'meeting_person.updated_by' => $this->updated_by,
            'meeting_person.updated_at' => $this->updated_at,
            'meeting_person.role_id' => $this->role_id,
        ]);

        return $dataProvider;
    }

}
