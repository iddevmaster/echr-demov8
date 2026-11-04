<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[CommitteeQualificationPanel]].
 *
 * @see CommitteeQualificationPanel
 */
class CommitteeQualificationPanelQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CommitteeQualificationPanel[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }
    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['committee_qualification_panel.deleted' => $deleted]);
    }
    /**
     * {@inheritdoc}
     * @return CommitteeQualificationPanel|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
