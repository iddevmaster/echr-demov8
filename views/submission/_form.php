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
    <?php
    $options = [];
    if (isset($action)) {
        $options['action'] = $action;
    }
    $form = ActiveForm::begin($options
//                'id' => 'form-submission',
//        'action' => \yii\helpers\Url::to(['submission/update', 'id' => $model->id])
    );
    echo $form->errorSummary($model);
    ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?php if ($mode == app\models\Submission::MODE_ASSESSTYPE) { ?>
        <?php
        if ($model->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) {
            $submissionType = ArrayHelper::map(app\models\SubmissionType::find()->isDeleted(FALSE)->internal(false)->group($model->submissionType->submission_type_group_id)->orderBy('CONVERT(submission_type.name USING TIS620) ASC')->all(), 'id', 'i18nName');
            echo $form->field($model, 'submission_type_id')->widget(Select2::classname(), [
                'data' => $submissionType,
                'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        }
        ?>

        <?= $form->field($model, 'assess_type')->dropDownList(app\models\Submission::getAssessTypeLabel(), []) ?>



    <?php } ?>    
    <?php if ($mode == app\models\Submission::MODE_GENERATECODE) { ?>

        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(Panel::find()->isDeleted(FALSE)->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'panelId')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

        echo $form->field($model, 'isFda')->radioList(Yii::$app->util->getYesNoLabels());
        ?> 
    <?php } ?>
    <?php if ($mode == app\models\Submission::MODE_MEETINGPLAN) { ?>
        <?php if (isset($model->meeting_plan_date) && isset($model->send_plan_date)) { ?>
            <div class="col-md-6 ">
                <div class="alert alert-info alert-dismissible">
                    <?=
                    Yii::t('app', 'วันที่ประมาณการประชุม : ');
                    if (isset($model->meeting_plan_date)) {
                        echo Yii::$app->formatter->format($model->meeting_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="alert alert-info alert-dismissible">
                    <?=
                    Yii::t('app', ' วันที่ประมาณการส่งผลการประเมิน : ');
                    if (isset($model->send_plan_date)) {
                        echo Yii::$app->formatter->format($model->send_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการ');
                    }
                    ?>
                </div>
            </div>
        <?php } else { ?>
            <?=
            $form->field($model, 'meeting_plan_date')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE
            ]);
            ?>
            <?=
            $form->field($model, 'send_plan_date')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE
            ]);
            ?>

            <?php
        }
    }
    ?>    
    <?php if ($mode == \app\models\Submission::MODE_ACCEPTCOMMITTEE) { ?>
        <div class="col-md-12 ">
            <div class="alert alert-info alert-dismissible">
                <?=
                Yii::t('app', 'วันที่ประมาณการประชุม : ');
                if (isset($model->meeting_plan_date)) {
                    echo Yii::$app->formatter->format($model->meeting_plan_date, 'date');
                } else {
                    echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                }
                ?>
                <?=
                Yii::t('app', ' วันที่ประมาณการส่งผลการประเมิน : ');
                if (isset($model->send_plan_date)) {
                    echo Yii::$app->formatter->format($model->send_plan_date, 'date');
                } else {
                    echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการ');
                }
                ?>
            </div>
        </div>
        <?= $form->field($model, 'statusCommittee')->radioList(app\models\SubmissionCommittee::getStatusLabelsForm()) ?>
        <?php if (!$model->isFromCrec()) { ?>
            <?= $form->field($model, 'can_meeting')->radioList([1 => Yii::t('app', 'ได้'), 0 => Yii::t('app', 'ไม่ได้')]); ?>
            <?= $form->field($model, 'remark_meeting')->textInput(['maxlength' => true]); ?>
        <?php } ?>
        <?= $form->field($model, 'remarkCommittee')->textInput(['maxlength' => true]) ?>


    <?php } ?>

    <?php if ($mode == app\models\Submission::MODE_SETSECRETARY) { ?>

        <div class="row">
            <div class="col-md-12 ">
                <div class="alert alert-info alert-dismissible">
                    <?=
                    Yii::t('app', 'วันที่ประมาณการประชุม : ');
                    if (isset($model->meeting_plan_date)) {
                        echo Yii::$app->formatter->format($model->meeting_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                    }
                    ?>
                    <?=
                    Yii::t('app', ' วันที่ประมาณการส่งผลการประเมิน : ');
                    if (isset($model->send_plan_date)) {
                        echo Yii::$app->formatter->format($model->send_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการ');
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php
                // Usage with ActiveForm and model
                $data = ArrayHelper::map(app\models\PersonRolePanel::find()->joinWith(['personRole', 'personRole.person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::SECRETARY, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'personRole.person.user_id', 'personRole.person.fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
                echo $form->field($model, 'secretary_person')->label(Yii::t('app', 'เลือกเลขาที่รับผิดชอบ'))->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => '', 'disabled' => $currentRole['role_id'] != \app\models\Role::STAFF],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
        </div>
    <?php } ?>



    <?php if ($mode == app\models\Submission::MODE_SETAGENDA) { ?>
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(app\models\Meeting::find()->isDeleted(FALSE)->panel($model->project->panel_id)->orderBy('id desc')->all(), 'id', 'fullNameWithDate');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'meetingId')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(\app\models\AgendaSubmissionType::find()->isDeleted(FALSE)->submissionType($model->submission_type_id)->all(), 'agenda_id', 'agenda.fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'agendaId')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    <?php } ?>

    <?php if ($mode == app\models\Submission::MODE_CERTIFICATE) { ?>
        <div class="row">
            <div class="col-md-4">
                <?=
                $form->field($model, 'certified_date')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                ])
                ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'certificate_no')->textInput(); ?>
            </div>                

            <div class="col-md-4">
                <?=
                $form->field($model, 'expire_at')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                ])
                ?>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'progress_period')->textInput(['type' => 'number']); ?>
            </div>
            <div class="col-md-6">
                <?=
                $form->field($model, 'next_progress_at')->widget(DateControl::className(), [
                    'type' => DateControl::FORMAT_DATE,
                ])
                ?>
            </div>
        </div>
        <h3 class="panel-title bg-info"><?= yii::t('app', 'ออกหนังสือแจ้งผล') ?></h3>
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
            'pjaxId' => 'index',
            'dataProvider' => $ardProvider,
        ]);
        ?> 

    <?php } ?>
    <?php if ($mode == app\models\Submission::MODE_CHECKDOC) { ?>
        <?php if ($model->is_legacy == 0) { ?>
            <div class="col-md-12">
                <?php if (!$model->isFromCrec()) { ?>
                    <?= $form->field($model, 'status')->radioList(\app\models\Submission::getStatusCheckDoc()) ?>
                <?php } else { ?>
                    <?= $form->field($model, 'status')->radioList(\app\models\Submission::getStatusCheckDocCrec()) ?>
                <?php } ?>

            </div>
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'remark_checkdoc_staff')->widget(CKEditor::className(), [
                    'options' => [
                        'id' => uniqid(),
                    ],
                    'editorOptions' => [
                        'preset' => 'standard',
                        'inline' => false,
                        'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
                    ],
                ]);
                ?>
            </div>
            <?php if ($model->submission_type_id == \app\models\SubmissionType::TYPE_AMENDMENT): ?>
                <div class="col-md-12">
                    <?= $form->field($model, 'event_amendment')->textInput(['maxlength' => true]) ?>
                </div>
            <?php endif; ?>
        <?php } else { ?>
            <div class="col-md-12">
                <?= $form->field($model, 'status')->radioList(\app\models\Submission::getStatusCheckDoc()) ?>
            </div>
            <div class="col-md-12"><?= $form->field($project, 'is_fda')->radioList(Yii::$app->util->getYesNoLabels()); ?></div>
            <div class="col-md-12">
                <?= $form->field($model, 'remark_checkdoc_staff')->textInput(); ?>
            </div>
            <div class="row">
                <div class="col-md-6"><?= $form->field($project, 'project_code')->textInput(); ?></div>
                <div class="col-md-6"><?php
                    // Usage with ActiveForm and model
                    $data = ArrayHelper::map(Panel::find()->isDeleted(FALSE)->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
                    echo $form->field($project, 'panel_id')->widget(Select2::classname(), [
                        'data' => $data,
                        'options' => ['placeholder' => 'เลือก Panel'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?></div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($mode == app\models\Submission::MODE_ASSESSEDCOMMITTEE) { ?>
        <?php if ($model->assess_type != \app\models\Submission::TYPE_FULLBOARD || isset($model->ref_submission_id)) { ?>
            <div class="col-md-12">
                <?php if (!$model->isFromCrec()) { ?>
                    <?= $form->field($model, 'status')->radioList(\app\models\Submission::getStatusAssessedCommittee()) ?>
                <?php } else { ?>
                    <?= $form->field($model, 'status')->radioList(\app\models\Submission::getStatusAssessedCommitteeCrec()) ?>
                <?php } ?>
            </div>
            <?php if (!$model->isFromCrec()) { ?>
                <div class="col-md-12"><?= $form->field($model, 'is_meeting')->radioList([2 => Yii::t('app', 'ไม่ต้องเข้าประชุม'), 1 => Yii::t('app', 'ต้องเข้าประชุม')]); ?></div>
            <?php } ?>
            <div class="col-md-12">    
                <?php if (!$model->isFromCrec()) { ?>
                    <?=
                    $form->field($model, 'resolution')->label(Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ'))->widget(Select2::className(), [
                        'data' => $model->resolutionConsiderationLables,
                        'options' => ['placeholder' => Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                <?php } else { ?>
                    <?=
                    $form->field($model, 'resolution')->label(Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ'))->widget(Select2::className(), [
                        'data' => $model->resolutionConsiderationLablesCrec,
                        'options' => ['placeholder' => Yii::t('app', 'ผลการพิจารณาเบื้องต้นของกรรมการ')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                <?php } ?>

            </div>  
            <div class="col-md-12"><?= $form->field($model, 'assess_type')->dropDownList(app\models\Submission::getAssessTypeLabel(), []) ?></div>
            <div class="col-md-12">
                <?php
                echo $form->field($model, 'remark_assessed_staff')->widget(CKEditor::className(), [
                    'options' => [
                        'id' => uniqid(),
                    ],
                    'editorOptions' => [
                        'preset' => 'standard',
                        'inline' => false,
                        'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
                    ],
                ]);
                ?>
            </div>
        <?php } else { ?>
            <?= $form->field($model, 'status')->radioList([app\models\Submission::STATUS_COMMITTEE_ASSESSED => \app\models\Submission::getStatusAssessedCommittee()[app\models\Submission::STATUS_COMMITTEE_ASSESSED]]) ?>
        <?php } ?>


    <?php } ?>

    <?php if ($model->status == app\models\Submission::STATUS_CODE_GENERATED || $model->status == app\models\Submission::STATUS_MEETING_APPOINTMENT) { ?>
        <?php if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) { ?>
            <div class="form-group">
                <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-submission']) ?>
            </div>
            <?php
        }
    }
    ?>
    <?php if ($model->status >= app\models\Submission::STATUS_MEETING_DONE) { ?>
        <?php if ($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) { ?>
            <div class="form-group">
                <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-submission']) ?>
            </div>
            <?php
        }
    }
    ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
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
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
            }
        });
    });     
js;
$this->registerJs($js);
?>