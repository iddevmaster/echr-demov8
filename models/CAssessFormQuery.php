<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CAssessForm]].
 *
 * @see CAssessForm
 */
class CAssessFormQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return CAssessForm[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CAssessForm|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['c_assess_form.deleted' => $deleted]);
    }
    
    public function submission($submissionId) {
        return $this->andWhere(['c_assess_form.submission_id' => $submissionId]);
    }
    
    public function submissionCommittee($submissionCommitteeId) {
        return $this->andWhere(['c_assess_form.submission_committee_id' => $submissionCommitteeId]);
    }
}
