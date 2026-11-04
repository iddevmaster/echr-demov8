<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PersonTraining]].
 *
 * @see PersonTraining
 */
class PersonTrainingQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return PersonTraining[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PersonTraining|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['person_training.deleted' => $deleted]);
    }

    public function person($personId) {
        return $this->andWhere(['person_training.person_id' => $personId]);
    }

    /**
     * Training records whose expiry is exactly $days away from today.
     */
    public function expiringInDays($days) {
        return $this->andWhere('person_training.expire_date IS NOT NULL')
                        ->andWhere(new \yii\db\Expression('DATEDIFF(person_training.expire_date, CURDATE()) = :d', [':d' => (int) $days]));
    }

    /**
     * Training records expired as of $asOfDate (default today).
     */
    public function expired($asOfDate = null) {
        $asOf = isset($asOfDate) ? $asOfDate : date('Y-m-d');
        return $this->andWhere('person_training.expire_date IS NOT NULL')
                        ->andWhere(['<=', 'person_training.expire_date', $asOf]);
    }

}
