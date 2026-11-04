<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionProjectResearcher]].
 *
 * @see SubmissionProjectResearcher
 */
class SubmissionProjectResearcherQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return SubmissionProjectResearcher[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionProjectResearcher|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_project_researcher.deleted' => $deleted]);
    }
    public function isPrDeleted($deleted = TRUE) {
        return $this->andWhere(['project_researcher.deleted' => $deleted]);
    }
    public function status($status) {
        return $this->andWhere(['submission_project_researcher.status' => $status]);
    }

    public function submission($submissionId) {
        return $this->andWhere(['submission_project_researcher.submission_id' => $submissionId]);
    }

    public function projectResearcher($prId) {
        return $this->andWhere(['submission_project_researcher.project_researcher_id' => $prId]);
    }

}
