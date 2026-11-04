<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DeviationEventEthics]].
 *
 * @see DeviationEventEthics
 */
class DeviationEventEthicsQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * {@inheritdoc}
     * @return DeviationEventEthics[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return DeviationEventEthics|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['deviation_event_ethics.deleted' => $deleted]);
    }
    
    public function deviationEvent($devEvId) {
        return $this->andWhere(['deviation_event_ethics.deviation_event_id' => $devEvId]);
    }

    public function ethics($ethicsId) {
        return $this->andWhere(['deviation_event_ethics.ethics_id' => $ethicsId]);
    }
}
