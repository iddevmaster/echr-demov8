<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ContinueAssessFormReview]].
 *
 * @see ContinueAssessFormReview
 */
class ContinueAssessFormReviewQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ContinueAssessFormReview[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ContinueAssessFormReview|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['continue_assess_form_review.deleted' => $deleted]);
    }
    
    public function continueAssessForm($formId) {
        return $this->andWhere(['continue_assess_form_review.continue_assess_form_id' => $formId]);
    }
    
    public function reviewChoice($reviewId) {
        return $this->andWhere(['continue_assess_form_review.review_choice_id' => $reviewId]);
    }

}
