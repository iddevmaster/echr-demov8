<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionCoiPerson]].
 *
 * @see SubmissionCoiPerson
 */
class SubmissionCoiPersonQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionCoiPerson[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionCoiPerson|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_coi_person.deleted' => $deleted]);
    }
    
    public function submission($submissionId) {
        return $this->andWhere(['submission_coi_person.submission_id' => $submissionId]);
    }
    
    public function person($personId) {
        return $this->andWhere(['submission_coi_person.person_id' => $personId]);
    }

}
