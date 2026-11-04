<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ProjectCodeHistory]].
 *
 * @see ProjectCodeHistory
 */
class ProjectCodeHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ProjectCodeHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ProjectCodeHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
