<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeviationEvent]].
 *
 * @see DeviationEvent
 */
class DeviationEventQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return DeviationEvent[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviationEvent|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['deviation_event.deleted' => $deleted])->andWhere(['submission.deleted' => $deleted]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['deviation_event.submission_id' => $submissionId]);
    }

    public function statusSubmission($status) {
//        return $this->andWhere(['>=', 'submission.status', $statusStart])->andWhere(['<=', 'submission.status', $statusEnd]);
        return $this->andWhere(['submission.status' => $status]);
    }

    public function project($projectId) {
        return $this->andWhere(['submission.project_id' => $projectId]);
    }

    public function isOngoing($projectId, $isOngoing) {
        $subQuery = (new \yii\db\Query())
                ->select('p.id')
                ->from('submission s')
                ->innerJoin('project p', 's.project_id=p.id')
                ->andWhere(['s.project_id' => $projectId])
                ->andWhere(['p.is_closed' => $isOngoing])
                ->andWhere(['s.deleted' => FALSE]);
        return $this->andWhere(['exists', $subQuery]);
    }

    public function submissionCommittee($submissionCommitteeId) {
        return $this->andWhere(['deviation_event.submission_committee_id' => $submissionCommitteeId]);
    }

    public function submissionEvent($submissionEventId) {
        return $this->andWhere(['deviation_event.submission_event_id' => $submissionEventId]);
    }

    public function isMajorMinorCom($mmc) {
        return $this->andWhere(['deviation_event.is_major_minor_com' => $mmc]);
    }

}
