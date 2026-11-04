<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[RunningCode]].
 *
 * @see RunningCode
 */
class RunningCodeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return RunningCode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RunningCode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
