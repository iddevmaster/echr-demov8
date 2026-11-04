<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[VolunteerNumber]].
 *
 * @see VolunteerNumber
 */
class VolunteerNumberQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return VolunteerNumber[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return VolunteerNumber|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['volunteer_number.deleted' => $deleted]);
    }

}
