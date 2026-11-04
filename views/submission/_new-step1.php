<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>
<?php if (isset($submission->refSubmission)): ?>
    <div class="col-md-12">
        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#refSubmissionConclusion"
                aria-expanded="false" aria-controls="exampleCollapseExample">
                    <?= Yii::t('app', 'รายละเอียดมติที่ประชุมครั้งก่อน') ?>
            (<?= Yii::t('app', 'คลิกเพื่ออ่าน') ?>)
        </button>
        <div class="collapse alert alert-danger" id="refSubmissionConclusion">
            <?= isset($submission->refSubmission->meetingAgenda->conclusion) ? $submission->refSubmission->meetingAgenda->conclusion : "" ?>
            <?php $result = app\models\SubmissionResultDocument::find()->isDeleted(false)->submission($submission->ref_submission_id)->all() ?>
            <p><?= Yii::t('app', 'เอกสารแจ้งผลการพิจารณา') ?></p>
            <?php foreach ($result as $re): ?>
                <li><?= $re->name; ?> <?= $re->fileIconHtml ?></li>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?php if (isset($submission->leader_comment)): ?>
    <div class="col-md-12">
        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#refSubmissionConclusion"
                aria-expanded="false" aria-controls="exampleCollapseExample">
                    <?= Yii::t('app', 'รายละเอียดในการแก้ไขเพิ่มเติม') ?>
            (<?= Yii::t('app', 'คลิกเพื่ออ่าน') ?>)
        </button>
        <div class="collapse alert alert-danger" id="refSubmissionConclusion">
            <?= $submission->leader_comment ?>
        </div>
    </div>
<?php endif; ?>

<div class="col-md-12">
    <?php
    $elSubject = Html::getInputId($model, 'subject');
    $typeInfoUrl = yii\helpers\Url::to(['submission-type/info']);
    $crec = SubmissionType::TYPE_CREC;

    echo $form->field($submission, 'submission_type_id')->widget(Select2::classname(), [
        'data' => $submissionTypes,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            "change" => "function() {
                var data = $(this).select2('data');
                console.log(data);
                if (data.length > 0 && data[0].id) {
                    $.ajax({
                        url: '{$typeInfoUrl}',
                        data: {id: data[0].id},
                        method: 'GET',
                        dataType: 'JSON',
                        success: function(res, textStatus, jqXHR) {
                            if (res.id == {$crec}) {
                                $('.crec').show();
                            } else {
                                $('.crec').hide();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
                        }
                    });
                }
            }",
        ],
    ]);
    ?>

</div>
<div class="col-md-12">
    <?= $form->field($model, 'name_thai')->textInput(['maxlength' => true, 'readonly' => isset($submission->ref_submission_id)]) ?>
</div>
<div class="col-md-12">
    <?= $form->field($model, 'name_eng')->textInput(['maxlength' => true, 'readonly' => isset($submission->ref_submission_id)]) ?>
</div>

<div class="col-md-6">
    <?php
    // Usage with ActiveForm and model
    $data = ArrayHelper::map(FundingSource::find()->isDeleted(FALSE)->orderBy('CONVERT(funding_source.name USING TIS620) ASC')->all(), 'id', 'i18nName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    echo $form->field($model, 'funding_source_id')->widget(Select2::classname(), [
        'data' => $data,
//        'options' => ['placeholder' => '', 'disabled' => isset($submission->ref_submission_id)],
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>     
</div>  
<div class="col-md-6">
    <?= $form->field($model, 'funding_source_description')->textInput(['maxlength' => true, 'readonly' => isset($submission->ref_submission_id)]) ?>
</div>
<div class="col-md-6">
    <?php if (isset($submission->ref_submission_id)): ?>
        <?= $form->field($model, 'is_child_project')->dropDownList([0 => Yii::t('app', 'ไม่'), 1 => Yii::t('app', 'ใช่')], ['disabled' => isset($submission->ref_submission_id)]) ?>
    <?php else: ?>
        <?= $form->field($model, 'is_child_project')->radioList([0 => Yii::t('app', 'ไม่'), 1 => Yii::t('app', 'ใช่')], ['itemOptions' => ['disabled' => isset($submission->ref_submission_id)]]) ?>
    <?php endif; ?>
</div>

<div class="col-md-6">
    <?= $form->field($model, 'fda_no')->textInput(['maxlength' => true, 'readonly' => isset($submission->ref_submission_id)]) ?>
</div>
<div class="col-md-6">
    <?= $form->field($submission, 'correspondence_no')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-6">
    <?=
    $form->field($submission, 'correspondence_at')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATE,
//        'disabled' => isset($submission->ref_submission_id)
    ])
    ?>
</div>
<div class="col-md-12 crec" style="<?= isset($model->submissionType) && $model->submission_type_id == SubmissionType::TYPE_CREC ? "" : "display:none" ?>">
    <?= $form->field($model, 'crec_number')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-12">
    <?= $form->field($model, 'remark')->textInput(['maxlength' => true, 'readonly' => isset($submission->ref_submission_id)]) ?>
</div>
<div class="form-group">
    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', 'ถัดไป'), ['class' => 'btn btn-primary btn-next', 'name' => 'nextStep', 'value' => \app\controllers\SubmissionController::NEW_STEP2]) ?>
    </div>
</div>
