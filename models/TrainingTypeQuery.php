<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TrainingType]].
 *
 * @see TrainingType
 */
class TrainingTypeQuery extends \yii\db\ActiveQuery {

    /**
     * {@inheritdoc}
     * @return TrainingType[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TrainingType|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['training_type.deleted' => $deleted]);
    }

    public function category($category) {
        return $this->andWhere(['training_type.category' => $category]);
    }

}
