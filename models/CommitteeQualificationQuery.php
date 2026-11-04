<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CommitteeQualification]].
 *
 * @see CommitteeQualification
 */
class CommitteeQualificationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CommitteeQualification[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['committee_qualification.deleted' => $deleted]);
    }
    /**
     * {@inheritdoc}
     * @return CommitteeQualification|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
