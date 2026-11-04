<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentSubmisstionType */
?>
<div class="document-submission-type-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'documentId' => $documentId,
        'submissionTypeId' => $submissionTypeId,
        'roleId' => $roleId
    ])
    ?>
</div>
