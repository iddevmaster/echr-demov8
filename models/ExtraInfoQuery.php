<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ExtraInfo]].
 *
 * @see ExtraInfo
 */
class ExtraInfoQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ExtraInfo[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ExtraInfo|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
