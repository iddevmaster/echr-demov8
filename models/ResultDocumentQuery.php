<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[ResultDocument]].
 *
 * @see ResultDocument
 */
class ResultDocumentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ResultDocument[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ResultDocument|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['result_document.deleted' => $deleted]);
    }    
}
