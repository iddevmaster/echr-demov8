<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AgendaResultDocument]].
 *
 * @see AgendaResultDocument
 */
class AgendaResultDocumentQuery extends \yii\db\ActiveQuery {
    /* public function active()
      {
      return $this->andWhere('[[status]]=1');
      } */

    /**
     * @inheritdoc
     * @return AgendaResultDocument[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AgendaResultDocument|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['agenda_result_document.deleted' => $deleted]);
    }

    public function agenda($agenda) {
        return $this->andWhere(['agenda_result_document.agenda_id' => $agenda]);
    }

    public function document($document) {
        return $this->andWhere(['agenda_result_document.result_document_id' => $document]);
    }

    public function resolution($resolution) {
        return $this->andWhere(['result_document.resolution' => $resolution]);
    }
    
    public function committeeResolution($resolution) {
        return $this->andWhere(['result_document.committee_resolution' => $resolution]);
    }

}
