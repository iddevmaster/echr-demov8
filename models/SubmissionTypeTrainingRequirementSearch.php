<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionTypeTrainingRequirement;

/**
 * SubmissionTypeTrainingRequirementSearch represents the model behind the search form.
 */
class SubmissionTypeTrainingRequirementSearch extends SubmissionTypeTrainingRequirement {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'submission_type_id', 'category', 'rule', 'created_by', 'updated_by'], 'integer'],
            [['deleted', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
        $query = SubmissionTypeTrainingRequirement::find()->joinWith('submissionType');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'submission_type_training_requirement.id' => $this->id,
            'submission_type_training_requirement.submission_type_id' => $this->submission_type_id,
            'submission_type_training_requirement.category' => $this->category,
            'submission_type_training_requirement.rule' => $this->rule,
            'submission_type_training_requirement.deleted' => $this->deleted,
        ]);

        return $dataProvider;
    }

}
