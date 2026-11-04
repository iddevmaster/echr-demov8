<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionVolunteerNumber]].
 *
 * @see SubmissionVolunteerNumber
 */
class SubmissionVolunteerNumberQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionVolunteerNumber[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionVolunteerNumber|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_volunteer_number.deleted' => $deleted]);
    }

    public function project($projectId) {
        return $this->andWhere(['submission_volunteer_number.project_id' => $projectId]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['submission_volunteer_number.submission_id' => $submissionId]);
    }

    public function volunteerNumber($volunteerNumberId) {
        return $this->andWhere(['submission_volunteer_number.volunteer_number_id' => $volunteerNumberId]);
    }

}
