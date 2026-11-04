<?php

namespace app\models;

use yii\db\conditions\BetweenColumnsCondition;
use yii\db\conditions\BetweenCondition;

/**
 * This is the ActiveQuery class for [[SubmissionCommittee]].
 *
 * @see SubmissionCommittee
 */
class SubmissionCommitteeQuery extends \yii\db\ActiveQuery
{
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionCommittee[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionCommittee|array|null
     */
    public function submission($submission)
    {
        return $this->andWhere(['submission_committee.submission_id' => $submission]);
    }

    public function person($personId)
    {
        return $this->andWhere(['submission_committee.person_id' => $personId]);
    }

    public function one($db = null)
    {
        return parent::one($db);
    }

    public function status($status)
    {
        return $this->andFilterCompare('submission_committee.status', $status);
    }
    public function question($submission)
    {
        return $this->andWhere(['questionnaire_answer.submission_id' => $submission]);
    }
    public function isDeleted($deleted = TRUE)
    {
        return $this->andWhere(['submission_committee.deleted' => $deleted]);
    }
    public function project($projectId)
    {
        return $this->andWhere(['submission_committee.project_id' => $projectId]);
    }
    public function committeePosition($cpId)
    {
        return $this->andWhere(['submission_committee.committee_position_id' => $cpId]);
    }

    public function betweenMeetingDate($startDate, $endDate) {
        $subQuery = (new \yii\db\Query())
            ->select('ssh.id')
            ->from('submission_status_history ssh')
            ->andWhere(['ssh.status' => Submission::STATUS_CODE_GENERATED])
            ->andWhere(new BetweenCondition('ssh.created_at', 'BETWEEN', $startDate, $endDate))
            ->andWhere('ssh.submission_id = submission_committee.submission_id');
        return $this->andWhere(['exists', $subQuery]);
    }
}
