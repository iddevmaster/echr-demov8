<?php

use yii\helpers\Html;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $personal app\models\RegisterTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<?= $form->field($model, 'user_id')->label(FALSE)->hiddenInput(['maxlength' => true]) ?>
<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
<?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
<?php
if ($step == app\controllers\PersonController::REGISTER_STEP3) {
    
}
?>

<div class="form-group">
    <div class="pull-left">
        <?= Html::submitButton(Yii::t('app', '<span><i class="icon wb-arrow-left" aria-hidden="true"></i> ก่อนหน้า </span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary btn-prev', 'name' => 'previousStep', 'value' => $step-1]) ?>
    </div>
    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', '<span>ถัดไป <i class="icon wb-arrow-right" aria-hidden="true"></i></span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary btn-next', 'name' => 'nextStep', 'value' => $step+1]) ?>
    </div>
</div>