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

$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="submission-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?= $form->field($model, 'status')->label('ยืนยันการส่งโครงการ')->radioList([app\models\Submission::STATUS_SUBMITTED => 'ใช่',app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER => 'ไม่']) ?>
        <?= $form->field($model, 'leader_comment')->textInput(['maxlength' => true]) ?>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
