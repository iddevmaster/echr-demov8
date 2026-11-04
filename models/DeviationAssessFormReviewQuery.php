<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeviationAssessFormReview]].
 *
 * @see DeviationAssessFormReview
 */
class DeviationAssessFormReviewQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return DeviationAssessFormReview[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviationAssessFormReview|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['deviation_assess_form_review.deleted' => $deleted]);
    }
    
    public function deviationAssessForm($formId) {
        return $this->andWhere(['deviation_assess_form_review.deviation_assess_form_id' => $formId]);
    }
    
    public function reviewChoice($reviewId) {
        return $this->andWhere(['deviation_assess_form_review.review_choice_id' => $reviewId]);
    }
}
