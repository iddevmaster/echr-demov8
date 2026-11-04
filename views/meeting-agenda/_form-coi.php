<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Agenda;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meeting-agenda-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model); ?>
    <?= $form->field($model, 'title')->label(FALSE)->hiddenInput(); ?>
    <?= $this->renderFile('@app/views/meeting/_coi.php', [
        'model' => $model->meeting,
    ]); ?>

    <?php ActiveForm::end(); ?>
</div>   
</div>
