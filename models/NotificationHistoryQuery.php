<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[NotificationHistory]].
 *
 * @see NotificationHistory
 */
class NotificationHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return NotificationHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return NotificationHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE)
    {
        return $this->andWhere(['notification_history.deleted' => $deleted]);
    }

    public function notifyType($type)
    {
        return $this->andWhere(['notification_history.notify_type' => $type]);
    }

    public function personTraining($personTrainingId)
    {
        return $this->andWhere(['notification_history.person_training_id' => $personTrainingId]);
    }

    public function notifyDays($days)
    {
        return $this->andWhere(['notification_history.notify_days' => $days]);
    }
}
