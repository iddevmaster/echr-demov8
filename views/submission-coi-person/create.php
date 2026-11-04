<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SubmissionCoiPerson */

?>
<div class="submission-coi-person-create">
    <?= $this->render('_form', [
        'model' => $model,
        'submissionId'=>$submissionId,
    ]) ?>
</div>
