<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-type-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_alert')->checkbox() ?>

    <?= $form->field($model, 'min_occur')->widget(MaskedInput::class, [
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
            ]) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
