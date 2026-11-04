<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Alert]].
 *
 * @see Alert
 */
class AlertQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return Alert[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Alert|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isAcknowledged($acknowledged = TRUE) {
        return $this->andWhere(['alert.is_acknowledged' => $acknowledged]);
    }
    
    public function isAlerted($alerted = TRUE) {
        if ($alerted) {
            return $this->andWhere(['not', ['alert.alerted_at' => NULL]]);
        } else {
            return $this->andWhere(['alert.alerted_at' => NULL]);
        }
    }

    public function user($userId) {
        return $this->andWhere(['alert.user_id' => $userId]);
    }
}
