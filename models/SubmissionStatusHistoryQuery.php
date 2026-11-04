<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionStatusHistory]].
 *
 * @see SubmissionStatusHistory
 */
class SubmissionStatusHistoryQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionStatusHistory[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionStatusHistory|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function status($status) {
        return $this->andWhere(['submission_status_history.status' => $status]);
    }
    public function submission($submissionId) {
        return $this->andWhere(['submission_status_history.submission_id' => $submissionId]);
    }

    public function committee() {
        return $this->andWhere(['submission_status_history.status' => Submission::STATUS_COMMITTEE_SELECTED]);
    }

}
