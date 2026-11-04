<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Agenda;
use bajadev\ckeditor\CKEditor;
use kartik\widgets\AlertBlock;
use kartik\widgets\Growl;
use app\models\Risk;
use app\models\Submission;
use kartik\datecontrol\DateControl;

\kartik\date\DatePickerAsset::register($this);
\app\assets\HotkeysAsset::register($this);
kartik\daterange\MomentAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="meeting-agenda-form">

    <?php
    echo $this->renderFile('@app/views/widgets/_growl.php');
    //    echo AlertBlock::widget([
    //        'useSessionFlash' => true,
    //        'type' => AlertBlock::TYPE_GROWL,
    //        'delay' => 2000,
    //        'alertSettings' => [
    //            'success' => [
    //                'type' => kartik\alert\Alert::TYPE_SUCCESS,
    //                'options' => [
    //                    'class' => 'dark',
    //                ],
    //            ],
    //            'danger' => [
    //                'type' => kartik\alert\Alert::TYPE_DANGER,
    //                'options' => [
    //                    'class' => 'dark',
    //                ],
    //            ],
    //            'pluginOptions' => [
    //                'showProgressbar' => true,
    //                'placement' => [
    //                    'from' => 'bottom',
    //                    'align' => 'right',
    //                ]
    //            ]
    //        ]
    //    ]);
    ?>
    <?= isset($model->submission->projectLeader->person_id) ? $model->submission->projectLeader->person->getAlertDeviationProtocolHtml() : "" ?>
    <?php
    // || ($currentRole['role_id'] == \app\models\Role::ADMIN)
    $form = ActiveForm::begin();
    //    echo $form->errorSummary(array_merge($answers, [$model]));
    //    foreach ($answers as $answer) {
    //        \yii\helpers\VarDumper::dump($answer->errors);
    //    }
    //    exit;
    ?>

    <div class="row update-meeting-agenda-info">
        <div class="pull-right">
            <?php if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) { ?>
                <?= Html::button(Yii::t('app', 'จำนวนกรรมการ'), ['class' => 'btn btn-primary btn-round btn-committee-count']) ?>
            <?php } ?>
            <?php if ((($model->meeting->checked_status == app\models\Meeting::CS_PENDING) && ($currentRole['role_id'] == \app\models\Role::STAFF && $model->meeting->checked_staff == Yii::$app->user->identity->id)) || (($model->meeting->checked_status == app\models\Meeting::CS_STAFF_CHECKED) && ($currentRole['role_id'] == \app\models\Role::SECRETARY)) || (($model->meeting->checked_status == app\models\Meeting::CS_PRE_CHECKED) && ($currentRole['role_id'] == \app\models\Role::PRESIDENT))) { ?>
                <?= Html::button(Yii::t('app', 'บันทึก (Ctrl+S)'), ['class' => 'btn btn-primary btn-round btn-save-update-info']) ?>
            <?php } ?>
            <?php if (!isset($model->meeting->end_time) && $currentRole['role_id'] == \app\models\Role::STAFF) { ?>
                <?= Html::button(Yii::t('app', 'บันทึก (Ctrl+S)'), ['class' => 'btn btn-primary btn-round btn-save-update-info']) ?>
            <?php } ?>

        </div>
    </div>

    <div class="pull-right">
        <?php if (Yii::$app->util->checkPermission('MeetingAgenda.CancelAgenda') && isset($model->submission) && ($model->submission->status >= Submission::STATUS_AGENDA_ADDED)) : ?>
            <?=
            Html::button(Yii::t('app', 'ยกเลิกบรรจุวาระ'), ['class' => 'btn btn-danger',
                'role' => 'modal-remote',
                'data-url' => \yii\helpers\Url::to(['meeting-agenda/cancel-agenda', 'id' => $model->id]),
                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                'data-request-method' => 'post',
                'data-confirm-title' => Yii::t('app', 'ยืนยันการยกเลิกบรรจุวาระ'),
                'data-confirm-message' => Yii::t('app', "ต้องการยกเลิกบรรจุวาระ {$model->sort_label} : {$model->submission->project->project_code} ใช่หรือไม่ ?"),
                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                'data-confirm-cancel' => Yii::t('app', 'ไม่')])
            ?>
        <?php endif; ?>
    </div>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th><span class="font-weight-900"><?= Yii::t('app', 'วาระที่') ?></span> <?= $model->sort_label ?></th>
                <th><span class="font-weight-900"><?= Yii::t('app', 'เลขที่โครงการ') ?></span> <?= $model->submission->project->project_code ?> <?= $model->submission->project->is_child_project ? " (" . Yii::t('app', 'เด็ก') . ")" : "" ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาไทย)') ?></span> <?= $model->submission->project->name_thai ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาอังกฤษ)') ?></span> <?= $model->submission->project->name_eng ?></td>
            </tr>
            <?php if (isset($model->submission->subject)) : ?>
                <tr>
                    <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'หมายเหตุ') ?></span> <?= $model->submission->subject ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td><span class="font-weight-900"><?= Yii::t('app', 'ผู้วิจัย'); ?></span> <?= isset($model->submission->projectLeader->person_id) ? $model->submission->projectLeader->person->fullName : "" ?></td>
                <td><span class="font-weight-900"><?= Yii::t('app', 'สังกัด'); ?></span> <?= isset($model->submission->projectLeader->person_id) ? $model->submission->projectLeader->person->fullOrg : "" ?></td>
            </tr>
            <?php
            $people = $model->coiPeople;
            if (count($people) > 0) :
                ?>
                <tr>
                    <td colspan="2">
                        <span class="font-weight-900">COI</span>
                        <?php
                        $names = ArrayHelper::getColumn($people, 'fullName');
                        echo implode(', ', $names);
                        ?>

                    </td>
                </tr>
                <?php
            endif;
            ?>
        </tbody>
    </table>

    <?php
    $descId = uniqid();
    echo $form->field($model, 'description')->widget(CKEditor::className(), [
            'options' => ['rows' => 4],
            'editorOptions' => [
                'language' => Yii::$app->language,
                'toolbar' => [
                    ['Bold', 'Italic', 'Underline'],
                    ['NumberedList', 'BulletedList'],
                    ['Source'],
                ],
                'height' => 150,
            ],
    ]);
    ?>
    <div class="issue1">
        <?php
        echo $form->field($model->submission, 'issue1')->widget(CKEditor::className(), [
            'options' => ['rows' => 4],
            'editorOptions' => [
                'language' => Yii::$app->language,
                'toolbar' => [
                    ['Bold', 'Italic', 'Underline'],
                    ['NumberedList', 'BulletedList'],
                    ['Source'],
                ],
                'height' => 150,
            ],
        ]);
        ?>
    </div>
    <div class="issue1-eng">
        <?php
        echo $form->field($model->submission, 'issue1_eng')->widget(CKEditor::className(), [
            'options' => ['rows' => 4],
            'editorOptions' => [
                'language' => Yii::$app->language,
                'toolbar' => [
                    ['Bold', 'Italic', 'Underline'],
                    ['NumberedList', 'BulletedList'],
                    ['Source'],
                ],
                'height' => 150,
            ],
        ]);
        ?>
    </div>

    <?php if ($model->need_resolution) : ?>
        <?=
        $form->field($model, 'conclusion')->widget(CKEditor::className(), [
            'options' => ['rows' => 4],
            'editorOptions' => [
                'language' => Yii::$app->language,
                'toolbar' => [
                    ['Bold', 'Italic', 'Underline'],
                    ['NumberedList', 'BulletedList'],
                    ['Source'],
                ],
                'height' => 150,
            ],
        ]);
        ?>
        <?php
        if ($model->submission->submissionType->risk_assessment) {
            echo $form->field($model->submission, 'risk_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Risk::find()->isDeleted(FALSE)->all(), 'id', 'name'),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        }
        if ($model->submission->submissionType->progress) :
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model->submission, 'progress_period')->textInput(['type' => 'number']); ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($model->submission, 'next_progress_at')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                    ])
                    ?>
                </div>
            </div>
            <?php
        endif;
        if ($model->submission->submissionType->certify) :
            ?>
            <div class="">
                <?php
                echo $form->field($model->submission, 'special_condition')->widget(CKEditor::class, [
                    'options' => ['rows' => 4],
                    'editorOptions' => [
                        'language' => Yii::$app->language,
                        'toolbar' => [
                            ['Bold', 'Italic', 'Underline'],
                            ['NumberedList', 'BulletedList'],
                            ['Source'],
                        ],
                        'height' => 150,
                    ],
                ]);
                ?>
            </div>
            <div class="row">


                <div class="col-md-6">
                    <?=
                    $form->field($model->submission, 'certified_date')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($model->submission, 'expire_at')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                    ])
                    ?>
                </div>
            </div>
            <?php
        endif;
        $resN = \app\models\Submission::RESOLUTION_N;
        echo $form->field($model, 'resolution')->widget(Select2::className(), [
            'data' => $model->submission->resolutionLabels,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "change" => "function() {
                    var data = $(this).select2('data');
//                    console.log(data);
                    if (data.length > 0 && data[0].id) {
                        if (data[0].id == '{$resN}') {
                            $('.issue2').show();
                        } else {
                            $('.issue2').hide();
                        }
                    }
                }",
            ],
        ]);
        ?>
        <?php
        if (!$model->submission->is_submit_by_api && $model->submission->project->hasCrecNumber() && ($model->submission->submission_type_id == app\models\SubmissionType::TYPE_INTERNAL_SAE || $model->submission->submission_type_id == app\models\SubmissionType::TYPE_DEVIATION)) {

            //if (($model->submission->submission_type_id == app\models\SubmissionType::TYPE_INTERNAL_SAE || $model->submission->submission_type_id == app\models\SubmissionType::TYPE_DEVIATION) && $model->submission->project->firstSubmission->submission_type_id == app\models\SubmissionType::TYPE_CREC) { 
            ?>
            <?= $form->field($model->submission, 'send_to_crec')->radioList(Yii::$app->util->getYesNoLabels()) ?>

        <?php } ?>
        <?=
        $this->render('_questions', [
            'answers' => $answers,
            'form' => $form,
            'meetingAgenda' => $model,
        ])
        ?>
        <?=
        $this->render('_submission-events', [
            'form' => $form,
            'meetingAgenda' => $model,
        ])
        ?>

        <div class="issue2" style="<?= $model->resolution == \app\models\Submission::RESOLUTION_N ? "" : "display:none;" ?>">
            <?php
            echo $form->field($model->submission, 'issue2')->widget(CKEditor::className(), [
                'options' => ['rows' => 4],
                'editorOptions' => [
                    'language' => Yii::$app->language,
                    'toolbar' => [
                        ['Bold', 'Italic', 'Underline'],
                        ['NumberedList', 'BulletedList'],
                        ['Source'],
                    ],
                    'height' => 150,
                ],
            ]);
            ?>
        </div>
        <?php
        echo $form->field($model, 'summary')->widget(CKEditor::className(), [
            'options' => ['rows' => 4],
            'editorOptions' => [
                'language' => Yii::$app->language,
                'toolbar' => [
                    ['Bold', 'Italic', 'Underline'],
                    ['NumberedList', 'BulletedList'],
                    ['Source'],
                ],
                'height' => 150,
            ],
        ]);
        ?>

    <?php endif; ?>
    <?php ActiveForm::end(); ?>

</div>

<?php
$committeeCountUrl = \yii\helpers\Url::to(['meeting-agenda/add-committee-count', 'id' => $model->id]);
$certifiedDate = Html::getInputId($model->submission, 'certified_date');
$expireAt = Html::getInputId($model->submission, 'expire_at');
$certifiedDateVal = isset($model->submission->certified_date) ? Yii::$app->formatter->asDate($model->submission->certified_date) : "";
//\yii\helpers\VarDumper::dump($certifiedDateVal);
//exit;
$expireAtVal = isset($model->submission->expire_at) ? Yii::$app->formatter->asDate($model->submission->expire_at) : "";
$nextProgressVal = isset($model->submission->next_progress_at) ? Yii::$app->formatter->asDate($model->submission->next_progress_at) : "";

$elProgressPeriod = Html::getInputId($model->submission, 'progress_period');
$elNextProgress = Html::getInputId($model->submission, 'next_progress_at');
$nextProgress = Html::getInputId($model->submission, 'next_progress_at');

$js = <<<js
    $('#{$certifiedDate}-disp-kvdate').kvDatepicker('update', '{$certifiedDateVal}');
    $('#{$expireAt}-disp-kvdate').kvDatepicker('update', '{$expireAtVal}');
    $('#{$nextProgress}-disp-kvdate').kvDatepicker('update', '{$nextProgressVal}');

    $('.btn-committee-count').click(function() {
        $.ajax({
            url: '{$committeeCountUrl}',
            method: 'POST',
//            dataType: 'JSON',
            success: function(res, textStatus, jqXHR) {
                loadInfo('{$model->id}');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
            }
        });
    });
                
//    console.log(CKEDITOR.instances);
    Object.keys(CKEDITOR.instances).forEach(function(key) {
        CKEDITOR.instances[key].on( 'key', editorKeySave);
    });
                
    $('#{$elProgressPeriod}').keyup(function() {
        if ($.trim($(this).val()) != '') {
            var m = moment().add($(this).val(), 'M');
            console.log(m.format('YYYY-MM-DD'));
            $('#{$elNextProgress}-disp-kvdate').kvDatepicker('update', m.format('DD/MM/YYYY'));
        }
    });
js;
$this->registerJs($js);
