<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SubmissionVolunteer;
use app\models\SubmissionVolunteerSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\GalleryImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-document-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model) ?>
    <?= $form->field($model, 'status')->radioList(\app\models\SubmissionDocument::getStatusLabels()) ?>
    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>