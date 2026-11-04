<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SubmissionDocument */

?>
<div class="submission-document-update">
    <?= $this->render('_form-check', [
        'model' => $model,
        'subVol' => $subVol,
        'subEvent' => $subEvent,
    ]) ?>
</div>
