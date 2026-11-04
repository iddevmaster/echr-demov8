<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Volunteer]].
 *
 * @see Volunteer
 */
class VolunteerQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return Volunteer[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Volunteer|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['volunteer.deleted' => $deleted]);
    }

    public function projectId($projectId) {
        return $this->andWhere(['volunteer.project_id' => $projectId]);
    }

    public function code($code) {
        return $this->andWhere(['volunteer.code' => $code]);
    }
}
