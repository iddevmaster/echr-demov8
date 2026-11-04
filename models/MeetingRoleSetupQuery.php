<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[MeetingRoleSetup]].
 *
 * @see MeetingRoleSetup
 */
class MeetingRoleSetupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return MeetingRoleSetup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return MeetingRoleSetup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
