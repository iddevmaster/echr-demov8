<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SaeVolunteerEthics]].
 *
 * @see SaeVolunteerEthics
 */
class SaeVolunteerEthicsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return SaeVolunteerEthics[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SaeVolunteerEthics|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['sae_volunteer_ethics.deleted' => $deleted]);
    }
    
    public function saeVolunteer($saeId) {
        return $this->andWhere(['sae_volunteer_ethics.sae_volunteer_id' => $saeId]);
    }

    public function ethics($ethicsId) {
        return $this->andWhere(['sae_volunteer_ethics.ethics_id' => $ethicsId]);
    }
}
