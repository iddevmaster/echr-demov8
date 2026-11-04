<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionTypeVolunteerNumber]].
 *
 * @see SubmissionTypeVolunteerNumber
 */
class SubmissionTypeVolunteerNumberQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionTypeVolunteerNumber[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionTypeVolunteerNumber|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function submissionType($submissionType) {
        return $this->andWhere(['submission_type_volunteer_number.submission_type_id' => $submissionType]);
    }

    public function volunteerNumber($volunteer) {
        return $this->andWhere(['submission_type_volunteer_number.volunteer_number_id' => $volunteer]);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_type_volunteer_number.deleted' => $deleted]);
    }

}
