<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ResultDocument;

/**
 * ResultDocumentSearch represents the model behind the search form about `app\models\ResultDocument`.
 */
class ResultDocumentSearch extends ResultDocument {

    private $_notInDocumentAgendaId;

    public function getNotInDocumentAgendaId() {
        return $this->_notInDocumentAgendaId;
    }

    public function setNotInDocumentAgendaId($agendaId) {
        $this->_notInDocumentAgendaId = $agendaId;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['id', 'created_by', 'updated_by', 'notInDocumentAgendaId'], 'integer'],
                [['name', 'resolution', 'committee_resolution', 'template_file', 'remark', 'deleted', 'created_at', 'updated_at', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = ResultDocument::find();
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
            'asc' => ['CONVERT(result_document.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(result_document.name USING TIS620)' => SORT_DESC],
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
            'result_document.id' => $this->id,
            'result_document.created_by' => $this->created_by,
            'result_document.created_at' => $this->created_at,
            'result_document.updated_by' => $this->updated_by,
            'result_document.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'result_document.name', $this->name])
                ->andFilterWhere(['like', 'result_document.resolution', $this->resolution])
                ->andFilterWhere(['like', 'result_document.committee_resolution', $this->committee_resolution])
                ->andFilterWhere(['like', 'result_document.template_file', $this->template_file])
                ->andFilterWhere(['like', 'result_document.remark', $this->remark])
                ->andFilterWhere(['like', 'result_document.deleted', $this->deleted]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);

        if (!empty($this->notInDocumentAgendaId)) {
            $subQuery = (new \yii\db\Query())->select('id')->from('agenda_result_document')->where(['agenda_result_document.agenda_id' => $this->notInDocumentAgendaId])->andWhere('agenda_result_document.result_document_id=result_document.id')->andWhere('agenda_result_document.deleted=0');
            $query->andWhere(['not exists', $subQuery]);
        }
        return $dataProvider;
    }

}
