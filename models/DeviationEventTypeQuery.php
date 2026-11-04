<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeviationEventType]].
 *
 * @see DeviationEventType
 */
class DeviationEventTypeQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return DeviationEventType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviationEventType|array|null
     */
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['deviation_event_type.deleted' => $deleted]);
    }

    public function project($projectId,$mmc) {
        $subQuery = (new \yii\db\Query())
        ->select('s.id')
        ->from('submission s,deviation_event d,project p')
        ->andWhere('deviation_event_type.submission_event_id = d.submission_event_id')
        ->andWhere('d.submission_id = s.id')
        ->andWhere('s.project_id = p.id')
        ->andWhere(['s.deleted' => FALSE])
        ->andWhere(['s.status' => Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT])
        ->andWhere(['d.is_major_minor_com' => $mmc])
        ->andWhere(['s.project_id' => $projectId]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function submissionEvent($seId) {
        return $this->andWhere(['deviation_event_type.submission_event_id' => $seId]);
    }

    public function deviationType($dtId) {
        return $this->andWhere(['deviation_event_type.deviation_type_id' => $dtId]);
    }

    public function one($db = null) {
        return parent::one($db);
    }

}
