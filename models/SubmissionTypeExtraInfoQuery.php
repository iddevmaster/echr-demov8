<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionTypeExtraInfo]].
 *
 * @see SubmissionTypeExtraInfo
 */
class SubmissionTypeExtraInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SubmissionTypeExtraInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionTypeExtraInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
