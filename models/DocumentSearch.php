<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Document;

/**
 * DocumentSearch represents the model behind the search form about `app\models\Document`.
 */
class DocumentSearch extends Document
{
    /**
     * @inheritdoc
     */
    private $_notInDocumentSubmissionTypeId;


    public function getNotInDocumentSubmissionTypeId() {
        return $this->_notInDocumentSubmissionTypeId;
    }

    public function setNotInDocumentSubmissionTypeId($submissionTypeId) {
        $this->_notInDocumentSubmissionTypeId = $submissionTypeId;
    }
    public function rules()
    {
        return [
            [['id', 'number', 'deleted', 'created_by', 'updated_by', 'role_id', 'notInDocumentSubmissionTypeId'], 'integer'],
            [['name', 'created_at', 'updated_at', 'template_file', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = Document::find();
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
            'asc' => ['CONVERT(document.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(document.name USING TIS620)' => SORT_DESC],
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
            'document.id' => $this->id,
            'document.number' => $this->number,
            'document.deleted' => $this->deleted,
            'document.created_by' => $this->created_by,
            'document.created_at' => $this->created_at,
            'document.updated_by' => $this->updated_by,
            'document.updated_at' => $this->updated_at,
            'document.role_id' => $this->role_id,
        ]);

        $query->andFilterWhere(['like', 'document.name', $this->name])
            ->andFilterWhere(['like', 'document.template_file', $this->template_file]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);

        if (!empty($this->notInDocumentSubmissionTypeId)) {
            $subQuery = (new \yii\db\Query())->select('id')->from('document_submission_type')->where(['document_submission_type.submission_type_id' => $this->notInDocumentSubmissionTypeId])->andWhere('document_submission_type.document_id=document.id')->andWhere('document_submission_type.deleted=0');
            $query->andWhere(['not exists', $subQuery]);
        }
        return $dataProvider;
    }
}
