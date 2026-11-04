<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $personal app\models\RegisterTransaction */
/* @var $form yii\widgets\ActiveForm */
?>




<div class="form-group">
    <div class="pull-left">
        <?= Html::submitButton('ก่อนหน้า', ['class' => 'btn btn-primary', 'name' => 'previous-step']) ?>
    </div>
    <div class="pull-right">
        <?= Html::submitButton('ถัดไป', ['class' => 'btn btn-primary', 'name' => 'next-step']) ?>
    </div>
</div>