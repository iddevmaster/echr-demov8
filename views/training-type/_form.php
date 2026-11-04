<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\TrainingType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-type-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->dropDownList(\app\models\TrainingType::getCategoryLabels(), [
                'prompt' => Yii::t('app', 'ไม่ระบุ (อื่นๆ)'),
            ])->hint(Yii::t('app', 'ใช้จับคู่กับเกณฑ์การอบรมตามประเภทโครงการ')) ?>

    <?= $form->field($model, 'validity_years')->widget(MaskedInput::class, [
                'options' => [
                    'class' => 'form-control',
                ],
                'clientOptions' => [
                    'alias' => 'numeric',
                    'prefix' => '',
                    'groupSeparator' => ',',
                    'allowMinus' => false,
                    'autoGroup' => true,
                    'autoUnmask' => true,
                    'unmaskAsNumber' => true,
                ]
            ])->hint(Yii::t('app', 'เว้นว่างไว้หากการอบรมประเภทนี้ไม่มีวันหมดอายุ')) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
