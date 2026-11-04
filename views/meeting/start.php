<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="meeting-form">
    <?php
    $form = ActiveForm::begin();
    ?>
    <?=
    $form->field($model, 'start_time')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_TIME,
    ])
    ?>

    <?php ActiveForm::end(); ?>

</div>
