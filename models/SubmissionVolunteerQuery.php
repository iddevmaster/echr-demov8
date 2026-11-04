<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionVolunteer]].
 *
 * @see SubmissionVolunteer
 */
class SubmissionVolunteerQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SubmissionVolunteer[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionVolunteer|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_volunteer.deleted' => $deleted]);
    }

    public function notId($id) {
        return $this->andWhere(['not', ['submission_volunteer.id' => $id]]);
    }

    public function projectId($projectId) {
        return $this->andWhere(['volunteer.project_id' => $projectId]);
    }

    public function submissionId($submissionId) {
        return $this->andWhere(['submission_volunteer.submission_id' => $submissionId]);
    }

    public function submissionStatus($status) {
        return $this->joinWith(['submission'])->andFilterCompare('submission.status', $status);
    }

    public function volunteerId($volunteerId) {
        return $this->andWhere(['submission_volunteer.volunteer_id' => $volunteerId]);
    }

    public function submissionOrNull($submissionId) {
        return $this->andWhere(['or', ['submission_volunteer.submission_id' => $submissionId], ['submission_volunteer.submission_id' => null]]);
    }

}
