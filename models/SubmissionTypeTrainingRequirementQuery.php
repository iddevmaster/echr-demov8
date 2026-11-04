<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionTypeTrainingRequirement]].
 *
 * @see SubmissionTypeTrainingRequirement
 */
class SubmissionTypeTrainingRequirementQuery extends \yii\db\ActiveQuery {

    /**
     * {@inheritdoc}
     * @return SubmissionTypeTrainingRequirement[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SubmissionTypeTrainingRequirement|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['submission_type_training_requirement.deleted' => $deleted]);
    }

    public function submissionType($submissionTypeId) {
        return $this->andWhere(['submission_type_training_requirement.submission_type_id' => $submissionTypeId]);
    }

}
