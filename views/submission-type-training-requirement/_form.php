<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\SubmissionType;
use app\models\SubmissionTypeGroup;
use app\models\TrainingType;
use app\models\SubmissionTypeTrainingRequirement;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionTypeTrainingRequirement */
/* @var $form yii\widgets\ActiveForm */

$submissionTypes = SubmissionType::find()
        ->where(['deleted' => 0, 'submission_type_group_id' => SubmissionTypeGroup::GROUP_NEW])
        ->orderBy('id')->all();
?>

<div class="submission-type-training-requirement-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'submission_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($submissionTypes, 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'เลือกประเภทโครงการ')],
        'pluginOptions' => ['allowClear' => true],
    ]) ?>

    <?= $form->field($model, 'category')->dropDownList(TrainingType::getCategoryLabels(), [
        'prompt' => Yii::t('app', 'เลือกหมวดการอบรม'),
    ]) ?>

    <?= $form->field($model, 'rule')->dropDownList(SubmissionTypeTrainingRequirement::getRuleLabels(), [
        'prompt' => Yii::t('app', 'เลือกเกณฑ์'),
    ]) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
