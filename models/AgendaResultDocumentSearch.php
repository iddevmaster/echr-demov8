<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AgendaResultDocument;

/**
 * AgendaResultDocumentSearch represents the model behind the search form about `app\models\AgendaResultDocument`.
 */
class AgendaResultDocumentSearch extends AgendaResultDocument {

    public $resolution, $committeeResolution;
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'agenda_id', 'result_document_id', 'created_by', 'updated_by'], 'integer'],
            [['remark', 'deleted', 'created_at', 'updated_at', 'resolution', 'committeeResolution'], 'safe'],
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
        $query = AgendaResultDocument::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $query->joinWith(['resultDocument']);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'agenda_result_document.id' => $this->id,
            'agenda_result_document.agenda_id' => $this->agenda_id,
            'agenda_result_document.result_document_id' => $this->result_document_id,
            'agenda_result_document.created_by' => $this->created_by,
            'agenda_result_document.created_at' => $this->created_at,
            'agenda_result_document.updated_by' => $this->updated_by,
            'agenda_result_document.updated_at' => $this->updated_at,
//            'result_document.resolution' => $this->resolution,
//            'result_document.committee_resolution' => $this->committeeResolution,
        ]);
        $query->andFilterWhere(['or', ['result_document.resolution' => $this->resolution], ['result_document.committee_resolution' => $this->committeeResolution]]);
        $query->andFilterWhere(['like', 'agenda_result_document.remark', $this->remark])
                ->andFilterWhere(['like', 'agenda_result_document.deleted', $this->deleted]);

        return $dataProvider;
    }

}
