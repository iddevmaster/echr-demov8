<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[PersonDocumentAudit]].
 *
 * @see PersonDocumentAudit
 */
class PersonDocumentAuditQuery extends \yii\db\ActiveQuery {

    /**
     * {@inheritdoc}
     * @return PersonDocumentAudit[]|array
     */
    public function all($db = null) {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PersonDocumentAudit|array|null
     */
    public function one($db = null) {
        return parent::one($db);
    }

    public function isDeleted($deleted = TRUE) {
        return $this->andWhere(['person_document_audit.deleted' => $deleted]);
    }

    public function person($personId) {
        return $this->andWhere(['person_document_audit.person_id' => $personId]);
    }

    public function docType($docType) {
        return $this->andWhere(['person_document_audit.doc_type' => $docType]);
    }

    public function ref($refId) {
        return $this->andWhere(['person_document_audit.ref_id' => $refId]);
    }

}
