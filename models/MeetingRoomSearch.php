<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MeetingRoom;

/**
 * MeetingRoomSearch represents the model behind the search form about `app\models\MeetingRoom`.
 */
class MeetingRoomSearch extends MeetingRoom
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deleted', 'created_by', 'updated_by'], 'integer'],
            [['name', 'created_at', 'updated_at', 'createdByUserProfile.fullName', 'updatedByUserProfile.fullName'], 'safe'],
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
        $query = MeetingRoom::find();
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
            'asc' => ['CONVERT(meeting_room.name USING TIS620)' => SORT_ASC],
            'desc' => ['CONVERT(meeting_room.name USING TIS620)' => SORT_DESC],
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
            'meeting_room.id' => $this->id,
            'meeting_room.deleted' => $this->deleted,
            'meeting_room.created_by' => $this->created_by,
            'meeting_room.created_at' => $this->created_at,
            'meeting_room.updated_by' => $this->updated_by,
            'meeting_room.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'meeting_room.name', $this->name]);
        $query->andFilterWhere(['like', 'CONCAT(createdByUserProfile.first_name, createdByUserProfile.last_name)', $this->getAttribute('createdByUserProfile.fullName')]);
        $query->andFilterWhere(['like', 'CONCAT(updatedByUserProfile.first_name, updatedByUserProfile.last_name)', $this->getAttribute('updatedByUserProfile.fullName')]);

        return $dataProvider;
    }
}
