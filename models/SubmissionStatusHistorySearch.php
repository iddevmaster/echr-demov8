<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SubmissionStatusHistory;

/**
 * SubmissionStatusHistorySearch represents the model behind the search form about `app\models\SubmissionStatusHistory`.
 */
class SubmissionStatusHistorySearch extends SubmissionStatusHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'submission_id', 'status', 'created_by'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = SubmissionStatusHistory::find();

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
            'submission_id' => $this->submission_id,
            //'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
        ]);
                $currentRole = \Yii::$app->session->get('currentRole');

        if ($currentRole['role_id'] == Role::RESEARCHER) {
                    $query->andFilterWhere(['and', ['!=', 'status', Submission::STATUS_COMMITTEE_SELECTED], ['!=', 'status', Submission::STATUS_COMMITTEE_ACCEPTED], ['!=', 'status', Submission::STATUS_COMMITTEE_ASSESSED]]);

        } else{
            $query->andFilterWhere(['status' => $this->status]);
        }
        return $dataProvider;
    }
}
