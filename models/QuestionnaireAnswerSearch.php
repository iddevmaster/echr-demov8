<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\QuestionnaireAnswer;

/**
 * QuestionnaireAnswerSearch represents the model behind the search form about `app\models\QuestionnaireAnswer`.
 */
class QuestionnaireAnswerSearch extends QuestionnaireAnswer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'submission_committee_id', 'submission_id', 'questionnaire_title_id', 'questionnaire_choice_id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['text_answer', 'created_at', 'updated_at'], 'safe'],
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
        $query = QuestionnaireAnswer::find();

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
            'id' => $this->id,
            'submission_committee_id' => $this->submission_committee_id,
            'submission_id' => $this->submission_id,
            'questionnaire_title_id' => $this->questionnaire_title_id,
            'questionnaire_choice_id' => $this->questionnaire_choice_id,
            'deleted' => $this->deleted,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'text_answer', $this->text_answer]);

        return $dataProvider;
    }
}
