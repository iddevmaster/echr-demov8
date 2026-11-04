<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\VolunteerNumber;

/**
 * VolunteerNumberSearch represents the model behind the search form about `app\models\VolunteerNumber`.
 */
class VolunteerNumberSearch extends VolunteerNumber {

    private $_notSubmissionTypeId;


    public function getNotSubmissionTypeId() {
        return $this->_notSubmissionTypeId;
    }

    public function setNotSubmissionTypeId($submissionTypeId) {
        $this->_notSubmissionTypeId = $submissionTypeId;
    }
    
    public function rules() {
        return [
                [['id', 'deleted', 'created_by', 'updated_by'], 'integer'],
                [['name', 'created_at', 'updated_at', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = VolunteerNumber::find();
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
            'asc' => ['CONVERT(volunteer_number.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(volunteer_number.name USING TIS620)' => SORT_DESC],
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
            'volunteer_number.id' => $this->id,
            'volunteer_number.deleted' => $this->deleted,
            'volunteer_number.created_by' => $this->created_by,
            'volunteer_number.created_at' => $this->created_at,
            'volunteer_number.updated_by' => $this->updated_by,
            'volunteer_number.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'volunteer_number.name', $this->name]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);
        
        if (!empty($this->notSubmissionTypeId)) {
            $subQuery = (new \yii\db\Query())->select('id')->from('submission_type_volunteer_number')->where(['submission_type_volunteer_number.submission_type_id' => $this->notSubmissionTypeId])->andWhere('submission_type_volunteer_number.volunteer_number_id=volunteer_number.id')->andWhere('submission_type_volunteer_number.deleted=0');
            $query->andWhere(['not exists', $subQuery]);
        }
        return $dataProvider;
    }

}
