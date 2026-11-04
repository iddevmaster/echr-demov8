<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SaeAssessForm]].
 *
 * @see SaeAssessForm
 */
class SaeAssessFormQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SaeAssessForm[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SaeAssessForm|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['sae_assess_form.deleted' => $deleted]);
    }
    
    public function submission($submissionId) {
        return $this->andWhere(['sae_assess_form.submission_id' => $submissionId]);
    }
    
    public function submissionCommittee($submissionCommitteeId) {
        return $this->andWhere(['sae_assess_form.submission_committee_id' => $submissionCommitteeId]);
    }
}
