<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Department;

/**
 * DepartmentSearch represents the model behind the search form about `app\models\Department`.
 */
class DepartmentSearch extends Department {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id', 'organization_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['name','name_eng', 'address', 'tel', 'email', 'website', 'created_at', 'updated_at', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = Department::find();
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
            'asc' => ['CONVERT(department.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(department.name USING TIS620)' => SORT_DESC],
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
            'department.id' => $this->id,
            'department.organization_id' => $this->organization_id,
            'department.deleted' => $this->deleted,
            'department.created_by' => $this->created_by,
            'department.created_at' => $this->created_at,
            'department.updated_by' => $this->updated_by,
            'department.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['or',['like', 'department.name', $this->name],['like', 'department.name_eng', $this->name]])
                ->andFilterWhere(['like', 'department.address', $this->address])
                ->andFilterWhere(['like', 'department.tel', $this->tel])
                ->andFilterWhere(['like', 'department.email', $this->email])
                ->andFilterWhere(['like', 'department.website', $this->website]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);

        return $dataProvider;
    }

}
