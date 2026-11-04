<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PersonRolePanel;

/**
 * PersonRolePanelSearch represents the model behind the search form about `app\models\PersonRolePanel`.
 */
class PersonRolePanelSearch extends PersonRolePanel {

    /**
     * @inheritdoc
     */
    private $_notInSubmissionId;
    public $roleId, $expertise;

    public function getNotInSubmissionId() {
        return $this->_notInSubmissionId;
    }

    public function setNotInSubmissionId($submissionId) {
        $this->_notInSubmissionId = $submissionId;
    }

    public function rules() {
        return [
                [['id', 'person_role_id', 'panel_id', 'deleted', 'created_by', 'updated_by', 'is_regular', 'notInSubmissionId', 'roleId'], 'integer'],
                [['created_at', 'panel_id', 'updated_at', 'expertise'], 'safe'],
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
        $query = PersonRolePanel::find();
        $query->joinWith(['personRole','personRole.person']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');->andWhere('person_role.person_id=submission_committee.person_id')
            return $dataProvider;
        }

        $query->andFilterWhere([
            'person_role_panel.id' => $this->id,
            'person_role_panel.person_role_id' => $this->person_role_id,
            'person_role_panel.panel_id' => $this->panel_id,
            'person_role_panel.deleted' => $this->deleted,
            'person_role_panel.created_by' => $this->created_by,
            'person_role_panel.created_at' => $this->created_at,
            'person_role_panel.updated_by' => $this->updated_by,
            'person_role_panel.updated_at' => $this->updated_at,
            'person_role_panel.is_regular' => $this->is_regular,
            'person_role.role_id' => $this->roleId,
//            'person_role.person.expertise' => $this->expertise,
        ]);
        $query->andFilterWhere(['like', 'person.expertise', $this->expertise]);
        if (!empty($this->notInSubmissionId)) {
            $submission = Submission::findOne($this->notInSubmissionId);
            $subQuery = (new \yii\db\Query())->select('sc.id')->from('submission_committee sc')->andWhere(['sc.deleted' => 0, 'sc.submission_id' => $this->notInSubmissionId])->andWhere('sc.person_id=person_role.person_id');
            $query->andWhere(['not exists', $subQuery]);


            $subcomQuery = (new \yii\db\Query())->select('pr.id')->from('project_researcher pr')->andWhere(['pr.deleted' => 0, 'pr.project_id' => $submission->project_id])->andWhere('pr.person_id=person_role.person_id');
            $query->andWhere(['not exists', $subcomQuery]);
        }

        return $dataProvider;
    }

}
