<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionCoiPerson;

/**
 * SubmissionCoiPersonSearch represents the model behind the search form about `app\models\SubmissionCoiPerson`.
 */
class SubmissionCoiPersonSearch extends SubmissionCoiPerson
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'submission_id', 'person_id', 'created_by','deleted', 'updated_by'], 'integer'],
            [[ 'created_at', 'updated_at'], 'safe'],
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
        $query = SubmissionCoiPerson::find();

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
            'submission_coi_person.id' => $this->id,
            'submission_coi_person.submission_id' => $this->submission_id,
            'submission_coi_person.person_id' => $this->person_id,
            'submission_coi_person.created_by' => $this->created_by,
            'submission_coi_person.created_at' => $this->created_at,
            'submission_coi_person.updated_by' => $this->updated_by,
            'submission_coi_person.updated_at' => $this->updated_at,
            'submission_coi_person.deleted' => $this->deleted,
        ]);

//        $query->andFilterWhere(['like', 'submission_coi_person.deleted', $this->deleted]);

        return $dataProvider;
    }
}
