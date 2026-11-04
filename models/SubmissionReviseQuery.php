<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[SubmissionRevise]].
 *
 * @see SubmissionRevise
 */
class SubmissionReviseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return SubmissionRevise[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SubmissionRevise|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
