<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeviationAssessForm]].
 *
 * @see DeviationAssessForm
 */
class DeviationAssessFormQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return DeviationAssessForm[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviationAssessForm|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['deviation_assess_form.deleted' => $deleted]);
    }
    
    public function submission($submissionId) {
        return $this->andWhere(['deviation_assess_form.submission_id' => $submissionId]);
    }
    
    public function submissionCommittee($submissionCommitteeId) {
        return $this->andWhere(['deviation_assess_form.submission_committee_id' => $submissionCommitteeId]);
    }

}
