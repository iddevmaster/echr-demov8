<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[RegisterTransaction]].
 *
 * @see RegisterTransaction
 */
class RegisterTransactionQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return RegisterTransaction[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RegisterTransaction|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['register_transaction.deleted' => $deleted]);
    }

    public function meeting($meetingId) {
        return $this->andWhere(['register_transaction.meeting_id' => $meetingId]);
    }

    public function person($personId) {
        return $this->andWhere(['register_transaction.person_id' => $personId]);
    }

    public function inMeetingAt($date) {
        return $this->andWhere([
            'or', [
                'and', ['<=', 'register_transaction.registered_at', $date], ['register_transaction.out_at' => NULL]
            ], [
                'and', ['<=', 'register_transaction.registered_at', $date], ['>=', 'register_transaction.out_at', $date]
            ]
        ]);
    }
}
