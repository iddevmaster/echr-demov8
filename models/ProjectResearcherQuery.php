<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectResearcher]].
 *
 * @see ProjectResearcher
 */
class ProjectResearcherQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return ProjectResearcher[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectResearcher|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['project_researcher.deleted' => $deleted]);
    }
    public function isDeletedSubmission($deleted = TRUE) {
        return $this->andWhere(['submission.deleted' => $deleted]);
    }

    public function project($projectId) {
        return $this->andWhere(['project_researcher.project_id' => $projectId]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['project_researcher.submission_id' => $submissionId]);
    }

    public function isLeader($isLeader = TRUE) {
        return $this->andWhere(['project_researcher.is_leader' => $isLeader]);
    }

    public function acknowledgeStatus($status) {
        return $this->andWhere(['project_researcher.acknowledge_status' => $status]);
    }

    public function ackToken($token) {
        return $this->andWhere(['project_researcher.ack_token' => $token]);
    }

    public function person($person) {
        return $this->andWhere(['project_researcher.person_id' => $person]);
    }

    public function coordinator($coId) {
        return $this->andWhere(['project.project_coordinator_id' => $coId]);
    }

}
