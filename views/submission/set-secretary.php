<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>

<div class="submission-update">
    <?=
    $this->render('_form-set-secretary', [
        'model' => $model,
        'action' => $action,
        'submission' => $submission
    ])
    ?>

</div>

