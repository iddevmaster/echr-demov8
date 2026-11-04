<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
?>
<div class="meeting-agenda-create">
<?php
if ($model->parent->agenda->is_submission) {
    echo $this->render('_form', [
        'model' => $model,
    ]);
} else {
    echo $this->render('_form-general', [
        'model' => $model,
    ]);
}
?>
</div>
