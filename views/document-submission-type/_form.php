<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentSubmisstionType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-submission-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_require')->radioList([0 => 'ไม่', 1 => 'ใช่']) ?>
    <?= $form->field($model, 'is_api')->radioList([0 => 'ไม่', 1 => 'ใช่']) ?>
    <div class="row">
        <div class="col-md-6"><?=
            $form->field($model, 'sort')->widget(MaskedInput::class, [
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
            ])
            ?></div>
    </div>           
    <?php if ($roleId == app\models\Role::COMMITTEE) { ?>

        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(\app\models\CommitteePosition::find()->isDeleted(0)->isCancel(0)->orderBy('CONVERT(committee_position.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'committee_position_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?> 
        <?php if ($submissionTypeId == app\models\SubmissionType::TYPE_C || $submissionTypeId == app\models\SubmissionType::TYPE_R) { ?>
            <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(\app\models\SubmissionType::find()->isDeleted(false)->group(\app\models\SubmissionTypeGroup::GROUP_NEW)->orderBy('CONVERT(submission_type.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($model, 'ref_submission_type_id')->label('อ้างอิงประเภทเอกสาร (เลือกข้อมูลนี้เฉพาะเอกสารประเมินแก้ไขผลการพิจารณา )')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?> 
        <?php } ?>
    <?php } ?>
    <?php if (($roleId == app\models\Role::RESEARCHER) && ($model->submission_type_id == 12 || $model->submission_type_id == 10)) { ?>
        <?= $form->field($model, 'is_event')->radioList([0 => 'ไม่', 1 => 'ใช่']) ?>

    <?php } ?>



    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
