<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionExtraInfo]].
 *
 * @see SubmissionExtraInfo
 */
class SubmissionExtraInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SubmissionExtraInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionExtraInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
