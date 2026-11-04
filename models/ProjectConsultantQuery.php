<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectConsultant]].
 *
 * @see ProjectConsultant
 */
class ProjectConsultantQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ProjectConsultant[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ProjectConsultant|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_consultant.deleted' => $deleted]);
    }

    public function isDeletedSubmission($deleted = TRUE) {
        return $this->andWhere(['submission.deleted' => $deleted]);
    }

    public function project($projectId) {
        return $this->andWhere(['project_consultant.project_id' => $projectId]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['project_consultant.submission_id' => $submissionId]);
    }

    public function isLeader($isLeader = TRUE) {
        return $this->andWhere(['project_consultant.is_leader' => $isLeader]);
    }

    public function acknowledgeStatus($status) {
        return $this->andWhere(['project_consultant.acknowledge_status' => $status]);
    }

    public function ackToken($token) {
        return $this->andWhere(['project_consultant.ack_token' => $token]);
    }

    public function person($person) {
        return $this->andWhere(['project_consultant.person_id' => $person]);
    }

}
