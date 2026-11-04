<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentSubmisstionType */
?>
<div class="document-submission-type-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'roleId' => $roleId,
        'submissionTypeId' => $submissionTypeId,
    ])
    ?>

</div>
