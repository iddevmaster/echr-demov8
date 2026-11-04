<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>

<div class="submission-update">
    <?=
    $this->render('_form-meeting-plan', [
        'model' => $model,
        'action' => $action,
//        'submission' => $submission
    ])
    ?>

</div>

