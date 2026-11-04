<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PersonSubmissionType]].
 *
 * @see PersonSubmissionType
 */
class PersonSubmissionTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PersonSubmissionType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PersonSubmissionType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
