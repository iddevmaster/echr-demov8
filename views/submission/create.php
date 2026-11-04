<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Submission */

?>
<div class="submission-create">
    <?= $this->render('_form', [
        'model' => $model,
        'submissionId' => $submissionId
    ]) ?>
</div>
