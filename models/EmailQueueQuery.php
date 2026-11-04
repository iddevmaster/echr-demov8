<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[EmailQueue]].
 *
 * @see EmailQueue
 */
class EmailQueueQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return EmailQueue[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return EmailQueue|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }
    
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['email_queue.deleted' => $deleted]);
    }
    
    public function unsent() {
        return $this->andWhere(['email_queue.mail_at' => NULL]);
    }

    public function modelId($modelId) {
        return $this->andWhere(['email_queue.model_id' => $modelId]);
    }
    
    public function type($type) {
        return $this->andWhere(['email_queue.type' => $type]);
    }
}
