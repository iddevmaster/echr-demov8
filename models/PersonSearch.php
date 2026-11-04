<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Person;

/**
 * PersonSearch represents the model behind the search form about `app\models\Person`.
 */
class PersonSearch extends Person {

    /**
     * @inheritdoc
     */
    private $_notInPersonRoleId;
    public $inPersonRoleId, $crecDepartmentId;

    public function getNotInPersonRoleId() {
        return $this->_notInPersonRoleId;
    }

    public function setNotInPersonRoleId($id) {
        $this->_notInPersonRoleId = $id;
    }

    public function rules() {
        return [
            [['id', 'title_id', 'role_id', 'user_id', 'deleted', 'created_by', 'updated_by', 'notInPersonRoleId', 'crecDepartmentId'], 'integer'],
            [['idcard_no', 'first_name', 'last_name', 'email', 'tel', 'created_at', 'updated_at', 'inPersonRoleId'], 'safe'],
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
        $query = Person::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'person.id' => $this->id,
            'person.title_id' => $this->title_id,
            'person.role_id' => $this->role_id,
            'person.user_id' => $this->user_id,
            'person.deleted' => $this->deleted,
            'person.email' => $this->email,
            'person.created_by' => $this->created_by,
            'person.created_at' => $this->created_at,
            'person.updated_by' => $this->updated_by,
            'person.updated_at' => $this->updated_at,
        ]);

//        $query->andFilterWhere(['like', 'idcard_no', $this->idcard_no])
//                ->andFilterWhere(['like', 'first_name', $this->first_name])
//                ->andFilterWhere(['like', 'last_name', $this->last_name])
//                ->andFilterWhere(['like', 'email', $this->email])
//                ->andFilterWhere(['like', 'tel', $this->tel]);

        $query->andFilterWhere(['or', ['like', 'CONCAT(first_name, last_name)', $this->first_name]]);


        if (!empty($this->notInPersonRoleId)) {
            $subQuery = (new \yii\db\Query())->select('id')->from('person_role')->where(['person_role.role_id' => $this->notInPersonRoleId])->andWhere('person_role.person_id=person.id')->andWhere('person_role.deleted=0');
            $query->andWhere(['not exists', $subQuery]);
        }
        if (!empty($this->inPersonRoleId)) {
            $this->inPersonRoleId = \is_array($this->inPersonRoleId) ? $this->inPersonRoleId : [$this->inPersonRoleId];
            $subQuery = (new \yii\db\Query())->select('id')->from('person_role')->where(['person_role.role_id' => $this->inPersonRoleId])->andWhere('person_role.person_id=person.id')->andWhere('person_role.deleted=0');
            $query->andWhere(['exists', $subQuery]);
        }

        if (!empty($this->crecDepartmentId)) {
            $query->joinWith(['department'])->crecDepartmentId($this->crecDepartmentId);
        }
        return $dataProvider;
    }

}
