<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Organization;

/**
 * OrganizationSearch represents the model behind the search form about `app\models\Organization`.
 */
class OrganizationSearch extends Organization {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['name','name_eng', 'created_at', 'updated_at', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = Organization::find();
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
            'asc' => ['CONVERT(organization.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(organization.name USING TIS620)' => SORT_DESC],
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
            'organization.id' => $this->id,
            'organization.deleted' => $this->deleted,
            'organization.created_by' => $this->created_by,
            'organization.created_at' => $this->created_at,
            'organization.updated_by' => $this->updated_by,
            'organization.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['or',['like', 'organization.name', $this->name],['like', 'organization.name_eng', $this->name]]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);

        return $dataProvider;
    }

}
