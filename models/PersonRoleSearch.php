<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PersonRole;

/**
 * PersonRoleSearch represents the model behind the search form about `app\models\PersonRole`.
 */
class PersonRoleSearch extends PersonRole {

    /**
     * @inheritdoc
     */
    private $_notInSubmissionId;
    public $expertise, $name, $personDeleted, $personOrg, $personDepartment, $personDivision, $coiId,$typeIds;

    public function getNotInSubmissionId() {
        return $this->_notInSubmissionId;
    }

    public function setNotInSubmissionId($submissionId) {
        $this->_notInSubmissionId = $submissionId;
    }

    public function rules() {
        return [
            [['id', 'person_id', 'role_id', 'sign', 'deleted', 'created_by', 'updated_by', 'status', 'notInSubmissionId', 'personDeleted', 'personOrg', 'personDepartment', 'personDivision', 'coiId'], 'integer'],
            [['created_at', 'updated_at', 'effective_date', 'effective_number', 'expire_date', 'expertise', 'name'], 'safe'],
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
        $query = PersonRole::find();
        $query->joinWith(['person']);

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
            'person_role.id' => $this->id,
            'person_role.person_id' => $this->person_id,
            'person_role.role_id' => $this->role_id,
            'person_role.sign' => $this->sign,
            'person_role.deleted' => $this->deleted,
            'person_role.created_by' => $this->created_by,
            'person_role.created_at' => $this->created_at,
            'person_role.updated_by' => $this->updated_by,
            'person_role.updated_at' => $this->updated_at,
            'person_role.effective_date' => $this->effective_date,
            'person_role.expire_date' => $this->expire_date,
            'person_role.status' => $this->status,
            'person.deleted' => $this->personDeleted,
            'person.organization_id' => $this->personOrg,
            'person.department_id' => $this->personDepartment,
            'person.division_id' => $this->personDivision,
        ]);

        $query->andFilterWhere(['like', 'person_role.effective_number', $this->effective_number]);
        $query->andFilterWhere(['like', 'person.expertise', $this->expertise]);
//        $query->andFilterWhere(['or', ['like', 'CONCAT(person.first_name, person.last_name)', $this->name]]);
        $query->andFilterWhere(['or', ['like', 'CONCAT(person.first_name, person.last_name)', $this->name], ['like', 'CONCAT(person.first_name_eng, person.last_name_eng)', $this->name]]);



        if (!empty($this->notInSubmissionId)) {
            $submission = Submission::findOne($this->notInSubmissionId);
            $subQuery = (new \yii\db\Query())->select('sc.id')->from('submission_committee sc')->andWhere(['sc.deleted' => 0, 'sc.submission_id' => $this->notInSubmissionId])->andWhere('sc.person_id=person_role.person_id');
            $query->andWhere(['not exists', $subQuery]);

            if (isset($this->coiId)) {
//                $subQuery = (new \yii\db\Query())->select('id')->from('submission_coi_person')->where(['submission_coi_person.role_id' => Role::COMMITTEE])->andWhere('person_role.person_id=person.id')->andWhere('person_role.deleted=0');
                $subQuery = (new \yii\db\Query())->select('person_id')->from('submission_coi_person')->where(['submission_coi_person.submission_id' => $this->notInSubmissionId])->andWhere('submission_coi_person.person_id=person_role.person_id')->andWhere('submission_coi_person.deleted=0');
                $query->andWhere(['not exists', $subQuery]);
            }

//            $subcomQuery = (new \yii\db\Query())->select('pr.id')->from('project_researcher pr')->andWhere(['pr.deleted' => 0, 'pr.project_id' => $submission->project_id])->andWhere('pr.person_id=person_role.person_id');
//            $query->andWhere(['not exists', $subcomQuery]);
        }

        return $dataProvider;
    }

}
