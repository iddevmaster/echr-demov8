<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionEvent]].
 *
 * @see SubmissionEvent
 */
class SubmissionEventQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SubmissionEvent[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionEvent|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_event.deleted' => $deleted]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['submission_event.submission_id' => $submissionId]);
    }

    public function event($event) {
        return $this->andWhere(['submission_event.id' => $event]);
    }

    public function eventNo($eventNo) {
        return $this->andWhere(['submission_event.event_no' => $eventNo]);
    }

}
