<?php

use app\models\Submission;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'notify_crec_result_leader')->radioList(Yii::$app->util->getYesNoLabels()) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>