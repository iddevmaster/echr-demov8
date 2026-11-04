<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ContinueAssessFormEthics]].
 *
 * @see ContinueAssessFormEthics
 */
class ContinueAssessFormEthicsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return ContinueAssessFormEthics[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ContinueAssessFormEthics|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['continue_assess_form_ethics.deleted' => $deleted]);
    }

    public function continueAssessForm($formId) {
        return $this->andWhere(['continue_assess_form_ethics.continue_assess_form_id' => $formId]);
    }

    public function ethics($ethicsId) {
        return $this->andWhere(['continue_assess_form_ethics.ethics_id' => $ethicsId]);
    }

}
