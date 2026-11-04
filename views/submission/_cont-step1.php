<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
use app\models\Project;
use kartik\datecontrol\DateControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .select2-container--krajee-bs3 .select2-results > .select2-results__options {
        max-height: 400px;
        overflow-y: auto;
    }
</style>
<?php if (isset($model->refSubmission)): ?>
    <div class="col-md-12">
        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#refSubmissionConclusion"
                aria-expanded="false" aria-controls="exampleCollapseExample">
                    <?= Yii::t('app', 'รายละเอียดมติที่ประชุมครั้งก่อน') ?>
            (<?= Yii::t('app', 'คลิกเพื่ออ่าน') ?>)
        </button>
        <div class="collapse alert alert-danger" id="refSubmissionConclusion">
            <?= isset($model->refSubmission->meetingAgenda->conclusion) ? $model->refSubmission->meetingAgenda->conclusion : "" ?>
            <?php $result = app\models\SubmissionResultDocument::find()->submission($model->ref_submission_id)->all() ?>
            <p><?= Yii::t('app', 'เอกสารแจ้งผลการพิจารณา') ?></p>
            <?php foreach ($result as $re): ?>
                <li><?= $re->name; ?> <?= $re->fileIconHtml ?></li>
            <?php endforeach; ?>
        </div>
    </div>    
<?php endif; ?>
<br>
<div class="col-md-12">
    <?php
    // Usage with ActiveForm and model
    $projectInfoUrl = Url::to(['project/get-info']);
    $volUrl = Url::to(['submission-volunteer/create', 'submissionId' => $model->id]);
    $data = ArrayHelper::map(Yii::$app->user->identity->ableToContinueProjects, 'id', 'codeName');
    $reloadUrl = Url::to(['submission/continue', 't' => time()]);
    $stId = Html::getInputId($model, 'submission_type_id');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    $submissionTypesJson = Json::encode($submissionTypes);

    $submissionTypesJsonCrec = Json::encode($submissionTypes);

    $filteredSubmissionTypes = array_filter($submissionTypes, function ($val, $key) {
        return $key == 15;
    }, ARRAY_FILTER_USE_BOTH);

    $filteredSubmissionTypesCrec = array_filter($submissionTypes, function ($val, $key) {
        return $key == 10 || $key == 15 || $key == 12;
    }, ARRAY_FILTER_USE_BOTH);
//     \yii\helpers\VarDumper::dump($filteredSubmissionTypesCrec, 10, TRUE);

    $filteredSubmissionTypesJson = Json::encode($filteredSubmissionTypes);
    $filteredSubmissionTypesJsonCrec = Json::encode($filteredSubmissionTypesCrec);
    echo $form->field($model, 'project_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            "change" => "function() {
                var data = $(this).select2('data');
                console.log(data);
                if (data.length > 0 && data[0].id) {
                    let url = '{$volUrl}';
                    let op = '&';
                    if (url.indexOf('?') == -1) {
                        op = '?';
                    }
                    $.ajax({
                        url: '{$projectInfoUrl}',
                        data: {id: data[0].id},
                        method: 'GET',
                        dataType: 'JSON',
                        success: function(res, textStatus, jqXHR) {
                            if (res.is_active == 0) {
                                $('#{$stId}').html('');;
                                const submissionTypes = JSON.parse('{$filteredSubmissionTypesJson}');
                                console.log(submissionTypes);
                                for (const key in submissionTypes) {
                                    var newOption = new Option(submissionTypes[key], key, false, false);
                                    $('#{$stId}').append(newOption).trigger('change');
                                        }
                                  
                            } else if (res.crec_number){
                                 $('#{$stId}').html('');;
                                const submissionTypes = JSON.parse('{$filteredSubmissionTypesJsonCrec}');
                                console.log(submissionTypes);
                                for (const key in submissionTypes) {
                                    var newOption = new Option(submissionTypes[key], key, false, false);
                                    $('#{$stId}').append(newOption).trigger('change');
                                }
                                    
                            } else {
                                $('#{$stId}').html('');
                                const submissionTypes = JSON.parse('{$submissionTypesJson}');
                                var newOption = new Option('', '', false, false);
                                $('#{$stId}').append(newOption);
                                for (const key in submissionTypes) {
                                    var newOption = new Option(submissionTypes[key], key, false, false);
                                    $('#{$stId}').append(newOption).trigger('change');
                                }
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
                        }
                    });
                    $('.btn-add-submission-volunteer').attr('href', url + op + 'projectId=' + data[0].id);
                    $.pjax.reload({container: '#crud-datatable-submission-volunteer-pjax', timeout: false, url: '{$reloadUrl}&projectId='+data[0].id, push: false, replace: true});
                }
            }",
        ],
    ]);
    ?>     
</div>  
<div class="col-md-12">
    <?php
    $elSubject = Html::getInputId($model, 'subject');
    $typeInfoUrl = yii\helpers\Url::to(['submission-type/info']);
    $internalSae = SubmissionType::TYPE_INTERNAL_SAE;
    $deviation = SubmissionType::TYPE_DEVIATION;
    echo $form->field($model, 'submission_type_id')->widget(Select2::classname(), [
        'data' => $submissionTypes,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true,
            'limit' => '10'
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
                            if (res.add_subject == 1) {
                                $('.subject').show();
                            } else {
                                $('#{$elSubject}').val('');
                                $('.subject').hide();
                            }
                            if (res.id == {$internalSae}) {
                                $('.volunteer').show();
                            } else {
                                $('.volunteer').hide();
                            }
                            if (res.id == {$deviation}) {
                                $('.deviation').show();
                            } else {
                                $('.deviation').hide();
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
<div class="col-md-12 subject" style="<?= isset($model->submissionType) && $model->submissionType->add_subject ? "" : "display:none;" ?>">
    <?php
    echo $form->field($model, 'subject')->textInput();
    ?>

</div>
<div class="col-md-6">
    <?= $form->field($model, 'correspondence_no')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-6">
    <?=
    $form->field($model, 'correspondence_at')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATE,
//        'disabled' => isset($submission->ref_submission_id)
    ])
    ?>
</div>
<div class="col-md-12 deviation" style="<?= isset($model->submissionType) && $model->submission_type_id == SubmissionType::TYPE_DEVIATION ? "" : "display:none" ?>">
    <?= $form->field($model, 'events')->textInput(['maxlength' => true]) ?>
</div>
<div class="col-md-12 volunteer" style="<?= isset($model->submissionType) && $model->submission_type_id == SubmissionType::TYPE_INTERNAL_SAE ? "" : "display:none" ?>">
    <?php
//    $subVolSearch = new \app\models\SubmissionVolunteerSearch();
//    $subVolSearch->submission_id = $model->id;
//    $subVolSearch->projectId = $model->project_id;
//    $subVolProvider = $subVolSearch->search([]);
    echo $this->renderFile('@app/views/submission-volunteer/index.php', [
        'form' => $form,
        'submission' => $model,
        'searchModel' => $subVolSearch,
        'dataProvider' => $subVolProvider,
    ]);
    ?>
</div>
<div class="form-group">
    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', 'ถัดไป'), ['class' => 'btn btn-primary btn-next', 'name' => 'nextStep', 'value' => \app\controllers\SubmissionController::NEW_STEP2]) ?>
    </div>
</div>
