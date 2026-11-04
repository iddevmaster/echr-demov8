<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionProjectConsultant]].
 *
 * @see SubmissionProjectConsultant
 */
class SubmissionProjectConsultantQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return SubmissionProjectConsultant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionProjectConsultant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
        public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_project_consultant.deleted' => $deleted]);
    }

    public function status($status) {
        return $this->andWhere(['submission_project_consultant.status' => $status]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['submission_project_consultant.submission_id' => $submissionId]);
    }

    public function projectConsultant($prId) {
        return $this->andWhere(['submission_project_consultant.project_consultant_id' => $prId]);
    }
}
