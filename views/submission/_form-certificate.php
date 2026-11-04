<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use bajadev\ckeditor\CKEditor;
use app\models\Submission;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

$currentRole = \Yii::$app->session->get('currentRole');

$revise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->all();
$hisCom = \app\models\SubmissionStatusHistory::find()->submission($submission->id)->committee()->one();

if ($submission->status >= Submission::STATUS_COMMITTEE_SELECTED || $submission->status < Submission::STATUS_AGENDA_ADDED) {
    $status = Submission::CUSTOM_STATUS_MEETING_PENDING;
} else {
    $status = $submission->status;
}
?>

<div class="submission-form">
    <?php
    $options = [];
    if (isset($action)) {
        $options['action'] = $action;
    }
    $form = ActiveForm::begin($options
//                'id' => 'form-submission',
//        'action' => \yii\helpers\Url::to(['submission/update', 'id' => $model->id])
    );
    ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?php if (($model->status >= Submission::STATUS_COMMITTEE_ASSESSED && $model->resolution == Submission::RESOLUTION_Y && $model->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) || (isset($model->crec_resolution) && $model->crec_resolution == Submission::RESOLUTION_Y && $model->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE)) { ?>

        <div class="row">
            <div class="col-md-4">
                <?=
                $form->field($model, 'certified_date')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                        // 'disabled' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)
                ])
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'certificate_no')->textInput(['disabled' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)]); ?>
            </div>                

            <div class="col-md-4">
                <?=
                $form->field($model, 'expire_at')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                        // 'disabled' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)
                ])
                ?>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'progress_period')->textInput(['type' => 'number', 'disabled' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)]); ?>
            </div>
            <div class="col-md-6">
                <?=
                $form->field($model, 'next_progress_at')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                        // 'disabled' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'special_condition')->widget(CKEditor::class, [
                    'options' => ['rows' => 4],
                    'editorOptions' => [
                        'language' => Yii::$app->language,
                        'toolbar' => [
                            ['Bold', 'Italic', 'Underline'],
                            ['NumberedList', 'BulletedList'],
                            ['Source'],
                        ],
                        'height' => 150,
                        'readOnly' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN),
                    ],
                ]);
                ?>        
            </div>
        </div>
    <?php } ?>

    <?php if ((isset($model->resolution) || !empty($revise) || ( $model->status >= Submission::STATUS_COMMITTEE_ASSESSED) || (isset($model->crec_resolution) && $model->crec_resolution == Submission::RESOLUTION_Y)) && ((((($model->resolution == Submission::RESOLUTION_C || $model->resolution == Submission::RESOLUTION_R || $model->resolution == Submission::RESOLUTION_W || $model->resolution == Submission::RESOLUTION_P || $model->resolution == Submission::RESOLUTION_N || $model->resolution == Submission::RESOLUTION_T || ($model->resolution == '' && !isset($model->crec_resolution))) && $model->resolution != Submission::RESOLUTION_Y) && $model->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) || $model->submissionType->resolution_label == app\models\SubmissionType::RES_ACKNOWLEDGE || ((($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->project->projectLeader->person->id == \Yii::$app->user->identity->person->id) || ($currentRole['role_id'] == \app\models\Role::COORDINATOR && ($model->project_coordinator_id == \Yii::$app->user->id || $model->project_coordinator_2nd_id == \Yii::$app->user->id || $model->project_coordinator_3rd_id == \Yii::$app->user->id))))))) { ?>
        <div class="row">
            <div class="col-md-4">
                <?=
                $form->field($model, 'last_keep_date')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                        // 'disabled' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)
                ])
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'issue1')->widget(CKEditor::class, [
                    'options' => ['rows' => 4],
                    'editorOptions' => [
                        'language' => Yii::$app->language,
                        'toolbar' => [
                            ['Bold', 'Italic', 'Underline'],
                            ['NumberedList', 'BulletedList'],
                            ['Source'],
                        ],
                        'height' => 150,
                        'readOnly' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN),
                    ],
                ]);
                ?>        
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'issue1_eng')->widget(CKEditor::class, [
                    'options' => ['rows' => 4],
                    'editorOptions' => [
                        'language' => Yii::$app->language,
                        'toolbar' => [
                            ['Bold', 'Italic', 'Underline'],
                            ['NumberedList', 'BulletedList'],
                            ['Source'],
                        ],
                        'height' => 150,
                        'readOnly' => !($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN),
                    ],
                ]);
                ?>        
            </div>
        </div>
    <?php } ?>

    <?php if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-submission']) ?>
        </div>
        <?php
    }
    ?>
    <h3 class="panel-title bg-info"><?= yii::t('app', 'หนังสือรับรอง') ?></h3>
    <?php
//                            $ma = $submission->meetingAgenda;
//                            $ardSearch = new \app\models\AgendaResultDocumentSearch();
//                            if (isset($submission->ref_submission_id) && !empty($submission->resolution)) {
//                                $ardSearch->agenda_id = $submission->refSubmission->meetingAgenda->agenda_id;
//                            } else {
//                                $ardSearch->agenda_id = isset($ma) ? $ma->agenda_id : NULL;
//                            }
//                            $ardSearch->deleted = 0;
//                            $ardSearch->resolution = $submission->resolution;
//                            $lastRevise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
////                              
//                            if (isset($lastRevise)) {
////                                  \yii\helpers\VarDumper::dump($revise);
//                                $ardSearch->committeeResolution = $lastRevise->resolution;
//                            }
//                            $ardProvider = $ardSearch->search([]);
    $ardProvider = new \yii\data\ArrayDataProvider([
        'allModels' => $submission->getResultDocuments()
    ]);
    echo $this->renderFile('@app/views/submission/letter-result.php', [
        'submission' => $submission,
        'pjaxId' => 'cer',
        'dataProvider' => $ardProvider,
    ]);
    ?> 



    <?php ActiveForm::end(); ?>

</div>
<?php
if ($model->status >= app\models\Submission::STATUS_AGENDA_ADDED) {
    $lkDate = Html::getInputId($model, 'last_keep_date');
    $lkDateVal = isset($model->last_keep_date) ? Yii::$app->formatter->asDate($model->last_keep_date) : "";

    $certifiedDate = Html::getInputId($model, 'certified_date');
    $certifiedDateVal = isset($model->certified_date) ? Yii::$app->formatter->asDate($model->certified_date) : "";
    $expireAt = Html::getInputId($model, 'expire_at');
    $expireAtVal = isset($model->expire_at) ? Yii::$app->formatter->asDate($model->expire_at) : "";
    $nextProgressAt = Html::getInputId($model, 'next_progress_at');
    $nextProgressAtVal = isset($model->next_progress_at) ? Yii::$app->formatter->asDate($model->next_progress_at) : "";

    $js = <<<js
        $('#{$certifiedDate}-disp-kvdate').kvDatepicker('update', '{$certifiedDateVal}');
        $('#{$expireAt}-disp-kvdate').kvDatepicker('update', '{$expireAtVal}');
        $('#{$nextProgressAt}-disp-kvdate').kvDatepicker('update', '{$nextProgressAtVal}');
        $('#{$lkDate}-disp-kvdate').kvDatepicker('update', '{$lkDateVal}');
js;
    $this->registerJs($js);
    if (!($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN)) {
        $js = <<<js
                 $('#{$nextProgressAt}-disp').prop('disabled', true);
                 $('#{$certifiedDate}-disp').prop('disabled', true);
                 $('#{$expireAt}-disp').prop('disabled', true);
                 $('#{$lkDate}-disp').prop('disabled', true);
js;
        $this->registerJs($js);
    }
}
$js = <<<js
    $('.btn-save-submission').click(function() {
        var form = $(this).closest('form');
//        alert(form);
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'POST',
            dataType: 'JSON',
            success: function(res, textStatus, jqXHR) {
                if (res.forceReload) {
                    $.pjax.reload(res.forceReload);
                }
                dlgPrimary.alert(res.content);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
            }
        });
    });     
js;
$this->registerJs($js);
?>