<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Title;
use app\models\Organization;
use app\models\Department;
use app\models\Position;
use app\models\JobCategory;
use app\models\Panel;
use app\models\Role;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\widgets\FileInput;

$currentRole = \Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <div class="row">
                <div class="col-lg-4">
            <?php
            $data = ArrayHelper::map(Title::find()->isDeleted(FALSE)->orderBy('CONVERT(title.name USING TIS620) ASC')->all(), 'id', 'i18nName');
            echo $form->field($model, 'title_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?> 
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'first_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'last_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'บันทึก'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
