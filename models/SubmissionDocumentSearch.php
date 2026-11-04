<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionDocument;

/**
 * SubmissionDocumentSearch represents the model behind the search form about `app\models\SubmissionDocument`.
 */
class SubmissionDocumentSearch extends SubmissionDocument {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'project_id', 'document_id', 'submission_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['name', 'file_name', 'status', 'remark', 'created_at', 'updated_at'], 'safe'],
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
        $query = SubmissionDocument::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['position' => SORT_ASC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $this->project_id,
            'document_id' => $this->document_id,
            'submission_id' => $this->submission_id,
            'deleted' => $this->deleted,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'file_name', $this->file_name])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

}
