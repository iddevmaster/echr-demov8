<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SaeVolunteer]].
 *
 * @see SaeVolunteer
 */
class SaeVolunteerQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SaeVolunteer[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SaeVolunteer|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['sae_volunteer.deleted' => $deleted]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['sae_volunteer.submission_id' => $submissionId]);
    }

    public function project($projectId) {
        return $this->andWhere(['submission.project_id' => $projectId]);
    }

    public function submissionCommittee($submissionCommitteeId) {
        return $this->andWhere(['sae_volunteer.submission_committee_id' => $submissionCommitteeId]);
    }

    public function volunteer($volunteerId) {
        return $this->andWhere(['sae_volunteer.volunteer_id' => $volunteerId]);
    }

        public function status($status) {
        $customValue = Submission::getCustomStatusValue($status);
        if (isset($customValue)) {
            return $this->andWhere(['>=', 'submission.status', $customValue['min']])->andWhere(['<', 'submission.status', $customValue['max']]);
        } else {
            return $this->andWhere(['submission.status' => $status]);
        }
    }
    public function statusGd() {
        return $this->andWhere(['>=', 'submission.status', Submission::STATUS_DOC_APPROVED])->andWhere(['<=', 'submission.status', Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT]);
    }

    public function isDead($dead = 1) {
        return $this->andWhere(['sae_volunteer.dead' => $dead]);
    }

    public function isCured($cured = 1) {
        return $this->andWhere(['sae_volunteer.cured' => $cured]);
    }

    public function isDrug($drug = 1) {
        return $this->andWhere(['sae_volunteer.drug' => $drug]);
    }

}
