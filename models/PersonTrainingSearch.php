<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PersonTraining;

/**
 * PersonTrainingSearch represents the model behind the search form about `app\models\PersonTraining`.
 */
class PersonTrainingSearch extends PersonTraining
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'person_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['name_thai_course', 'name_eng_course', 'start_date', 'end_date', 'remark', 'file', 'created_at', 'updated_at','createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = PersonTraining::find();
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
        $dataProvider->sort->attributes['name_thai_course'] = [
            'asc' => ['CONVERT(person_training.name_thai_course USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(person_training.name_thai_course USING TIS620)' => SORT_DESC],
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
            'person_training.id' => $this->id,
            'person_training.person_id' => $this->person_id,
            'person_training.start_date' => $this->start_date,
            'person_training.end_date' => $this->end_date,
            'person_training.deleted' => $this->deleted,
            'person_training.created_by' => $this->created_by,
            'person_training.created_at' => $this->created_at,
            'person_training.updated_by' => $this->updated_by,
            'person_training.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'person_training.name_thai_course', $this->name_thai_course])
            ->andFilterWhere(['like', 'person_training.name_eng_course', $this->name_eng_course])
            ->andFilterWhere(['like', 'person_training.remark', $this->remark])
            ->andFilterWhere(['like', 'person_training.file', $this->file]);

        return $dataProvider;
    }
}
