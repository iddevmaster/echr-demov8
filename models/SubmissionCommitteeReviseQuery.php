<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionCommitteeRevise]].
 *
 * @see SubmissionCommitteeRevise
 */
class SubmissionCommitteeReviseQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionCommitteeRevise[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionCommitteeRevise|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_committee_revise.deleted' => $deleted]);
    }

    public function submission($submission) {
        return $this->andWhere(['submission_committee_revise.submission_id' => $submission]);
    }

}
