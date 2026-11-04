<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ProjectQuestion;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectQuestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-question-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'answer_type')->dropDownList(ProjectQuestion::answerTypeLabels()) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
