<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SubmissionVolunteer;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionVolunteer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-volunteer-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'volunteerCode')->textInput() ?>
    <?= $form->field($model, 'type')->dropDownList(SubmissionVolunteer::typeLabels()) ?>
    <?= $form->field($model, 'follow_up_no')->textInput(['type' => 'number']) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
