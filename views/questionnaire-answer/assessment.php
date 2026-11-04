<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\QuestionnaireTitle;
use yii\bootstrap\Tabs;
use app\models\Submission;
use app\models\SubmissionType;
use app\models\SubmissionTypeGroup;
use yii\helpers\Url;

kartik\spinner\SpinnerAsset::register($this);
$currentRole = Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\Tambon */
$this->title = Yii::t('app', 'ประเมินงานวิจัย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายการโครงการวิจัย'), 'url' => ['submission/index', 'committeeStatus' => app\models\SubmissionCommittee::STATUS_ACCEPTED]];
//$this->params['breadcrumbs'][] = ['label' => 'เลือกประเภทโครงการวิจัย', 'url' => ['document-submission-type/index', 'roleId' => $roleId]];
$this->params['breadcrumbs'][] = $this->title;
$re = \app\models\SubmissionResultDocument::find()->submission($submission->id)->isDeleted(FALSE)->one();
$new = \app\models\Submission::find()->isDeleted(FALSE)->refSubmission($submission->id)->one();
$ref = \app\models\Submission::findOne($submission->ref_submission_id);


//foreach ($answers as $key => $answer):
//    $as = $answer->id;
//endforeach;
//foreach ($submissionDocs as $key => $doc):
//    
//    $d = $doc->id;
//endforeach;
//\yii\helpers\VarDumper::dump($curReqDocs, 10, TRUE);
//\yii\helpers\VarDumper::dump($allReqDocs, 10, true);
//exit;
?>

<div class="assessment">

    <div class="panel-heading text-right">

    </div>
    <h3><?= $submission->project->project_code . ' : ' . $submission->project->name_thai ?></h3>
    <h3><?= 'ประเภทการรายงาน' . ' : ' . $submission->submissionType->name ?> <?= 'ผู้ประเมิน' . ' : ' . $submissionCommittee->person->fullName ?></h3>
    <div class="panel">
        <div class="alert alert-danger">
            <div class="row">
                <?= Yii::t('app', 'หมายเหตุ : ผู้ประเมินจะต้องทำการอัพโหลดไฟล์ประเมินจึงจะสามารถส่งแบบประเมินได้ค่ะ') ?> 
            </div>
        </div>
        <br>
        <div class="panel-body">
            <?= $submission->projectLeader->person->getAlertDeviationProtocolHtml() ?>
            <?php
            //echo Html::errorSummary([$answers]);
            $upload = '';
            if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW
                || $submission->isFromCrec()
            ) {
                $upload = $this->renderFile('@app/views/submission-committee-document/index-committee.php', [
//                'project' => $project,
                    'submission' => $submission,
                    'submissonDocs' => $submissionDocs,
                    'submissionDoc' => $submissionDoc,
                    'dataProvider' => $subDocProvider,
                    'sCommitteeId' => $sCommitteeId,
                    'reloadUrl' => ['questionnaire-answer/assessment', 'submissionId' => $submission->id, 'sCommitteeId' => $sCommitteeId],
                ]);
            }
            $assessContent = '';
            if (isset($assessForm) && !$submission->isFromCrec()) {
                $assessContent = $this->renderFile('@app/views/questionnaire-answer/assessment-form.php', [
                    'submission' => $submission,
                    'staf' => $staf,
                    'assessForm' => $assessForm,
                    'assessFormParams' => $assessFormParams,
                    'answers' => $answers,
                    'sCommitteeId' => $sCommitteeId,
                ]);
            }
//                            
            $submissionDocument = $this->renderFile('@app/views/submission-document/index-committee.php', [
                'submission' => $submission,
                'searchModel' => $docsearchModel,
                'dataProvider' => $docdataProvider,
//                'upload' => $upload
            ]);

            $researcher = $this->renderFile('@app/views/project-researcher/researcher.php', [
                'submission' => $submission,
                'searchModel' => $pResearchersearchModel,
                'dataProvider' => $pResearcherdataProvider
            ]);
            if (isset($submission->ref_submission_id)) {
                $refId = $ref->getResultDocuments();
            } else {
                $refId = $submission->getResultDocuments();
            }
            $ardProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $refId
            ]);

            $submissionResult = $this->renderFile('@app/views/submission/letter-result.php', [
                'submission' => $submission,
                'pjaxId' => 'index',
                'dataProvider' => $ardProvider,
            ]);
            $subSearchModel = new \app\models\SubmissionSearch();
            $subSearchModel->deleted = 0;
            $subSearchModel->project_id = $submission->project_id;
            $subDataProvider = $subSearchModel->search([]);
            $subDataProvider->sort->defaultOrder = [
                'id' => SORT_DESC,
            ];
            $submissionHistory = $this->renderFile('@app/views/project/submission-history.php', [
//                            'id' => $submission,
//                            'submissionId' => $submission->id,
                'searchModel' => $subSearchModel,
                'dataProvider' => $subDataProvider,
            ]);

            $items = [
                [
                    'label' => 'เอกสารโครงการและการแนบไฟล์ประเมิน',
                    'content' => $submissionDocument . $upload . $assessContent,
                    'active' => true
                ],
                [
                    'label' => 'รายชื่อนักวิจัย',
                    'content' => $researcher,
                ],
                [
                    'label' => 'หนังสือแจ้งผล',
                    'content' => $submissionResult,
                    'visible' => isset($submission->ref_submission_id)
                ],
//                [
//                    'label' => 'ประวัติการส่งโครงการ',
//                    'content' => $submissionHistory,
//                ],
            ];


            if (isset($new) || $submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_CONT) {
                $items[] = [
                    'label' => 'ประวัติการส่งโครงการ',
                    'content' => $submissionHistory,
                ];
            }

//            if (isset($assessForm)) {
//                $items[] = [
//                    'label' => 'กรอกแบบประเมินงานวิจัย',
//                    'content' => $this->render('assessment-form', [
//                        'submission' => $submission,
//                        'staf' => $staf,
//                        'assessForm' => $assessForm,
//                        'assessFormParams' => $assessFormParams,
//                        'answers' => $answers,
//                        'sCommitteeId' => $sCommitteeId,
//                    ]),
//                ];
//            }
//            if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
//                $items[] = [
//                    'label' => 'แนบไฟล์แบบประเมิน',
//                    'content' => $upload,
//                ];
//            }

            echo Tabs::widget([
                'itemOptions' => [
                    'class' => 'padding-top-15'
                ],
                'items' => $items,
            ]);
            ?>
            <?php \yii\widgets\Pjax::begin(['id' => 'submission-btn-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
            <?php
            $allReqDocs = $submission->submissionType->getRequireCommitteeDocumentSubmissTypes($submissionCommittee->committee_position_id, $submission);
            $curReqDocs = $submissionCommittee->getRequireSubmissionDocuments();
        //    \yii\helpers\VarDumper::dump($submissionCommittee);
//            \yii\helpers\VarDumper::dump(count($allReqDocs));
//            \yii\helpers\VarDumper::dump(count($curReqDocs));
        //    exit;
            if ($submissionCommittee->status == \app\models\SubmissionCommittee::STATUS_ACCEPTED) {
                if (isset($submissionCommittee->committee_position_id)) {
                    // if (!isset($assessForm)) {
                    if (empty($assessContent)) {
                        if (count($allReqDocs) == count($curReqDocs)) {
                            echo Html::a(Yii::t('app', 'ยืนยันการส่งการประเมิน'), ['submission/assessed-committee', 'id' => $submission->id, 'subCommitteeId' => $sCommitteeId, 'mode' => app\models\Submission::MODE_ASSESSEDCOMMITTEE], ['class' => 'btn btn-success btn-raised pull-right margin-bottom-20',
                                'role' => 'modal-remote', 'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการประเมิน'),
                                'data-confirm-message' => Yii::t('app', 'ยืนยันการส่งการประเมินใช่หรือไม่?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่')]);
                        }
                    } else {
                        $assessed = false;
//                    if ($staf->assess_form == SubmissionType::FORM_CONTINUE) {
//                        
//                    } else if ($staf->assess_form == SubmissionType::FORM_SAE) {
//                        $assessed = isset($assessForm->id) && isset($assessForm->resolution_id);
//                    } else if ($staf->assess_form == SubmissionType::FORM_C) {
//                        
//                    }
                        $assessed = isset($assessForm->id) && (isset($assessForm->review_choice_id) || isset($assessForm->opinion));
                        if ($assessed) {
                            echo Html::a(Yii::t('app', 'ยืนยันการส่งการประเมิน'), ['submission/assessed-committee', 'id' => $submission->id, 'subCommitteeId' => $sCommitteeId, 'mode' => app\models\Submission::MODE_ASSESSEDCOMMITTEE], ['class' => 'btn btn-success btn-confirm-assess btn-raised pull-right margin-bottom-20', 'role' => 'modal-remote',
                                'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการประเมิน'),
                                'data-confirm-message' => Yii::t('app', 'ยืนยันการส่งการประเมินใช่หรือไม่?'),
                                'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                'data-confirm-cancel' => Yii::t('app', 'ไม่')
                            ]);
                        }
                    }
                } else {
                    echo Html::a(Yii::t('app', 'ยืนยันการส่งการประเมิน'), ['submission/assessed-committee', 'id' => $submission->id, 'subCommitteeId' => $sCommitteeId, 'mode' => app\models\Submission::MODE_ASSESSEDCOMMITTEE], ['class' => 'btn btn-success btn-raised pull-right margin-bottom-20', 'role' => 'modal-remote', 'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการประเมิน'),
                        'data-confirm-message' => Yii::t('app', 'ยืนยันการส่งการประเมินใช่หรือไม่?'),
                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                        'data-confirm-cancel' => Yii::t('app', 'ไม่')]);
                }
            }
//        if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {
//            $allReqDocs = $submission->submissionType->getRequireCommitteeDocumentSubmissTypes($submissionCommittee->committee_position_id);
//            $curReqDocs = $submissionCommittee->getRequireSubmissionDocuments();
//            if (count($allReqDocs) == count($curReqDocs)) {
//                echo Html::a(Yii::t('app', 'ยืนยันการส่งการประเมิน'), ['submission/assessed-committee', 'id' => $submission->id, 'subCommitteeId' => $sCommitteeId, 'mode' => app\models\Submission::MODE_ASSESSEDCOMMITTEE], ['class' => 'btn btn-success btn-raised', 'role' => 'modal-remote', 'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการประเมิน'),
//                    'data-confirm-message' => Yii::t('app', 'ยืนยันการส่งการประเมินใช่หรือไม่?'),
//                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
//                    'data-confirm-cancel' => Yii::t('app', 'ไม่')]);
//            }
//        } else {
//            if (!isset($assessForm) || (isset($assessForm) && !$assessForm->isNewRecord)) {
//                echo Html::a(Yii::t('app', 'ยืนยันการส่งการประเมิน'), ['submission/assessed-committee', 'id' => $submission->id, 'subCommitteeId' => $sCommitteeId, 'mode' => app\models\Submission::MODE_ASSESSEDCOMMITTEE], ['class' => 'btn btn-success btn-raised', 'role' => 'modal-remote', 'data-confirm-title' => Yii::t('app', 'ยืนยันการส่งการประเมิน'),
//                    'data-confirm-message' => Yii::t('app', 'ยืนยันการส่งการประเมินใช่หรือไม่?'),
//                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
//                    'data-confirm-cancel' => Yii::t('app', 'ไม่')]);
//            }
//        }
                ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>

        </div>

    </div>
    <?php
    $confirmMsg = Yii::t('app', 'ต้องการดาวน์โหลดเอกสารที่เลือกใช่หรือไม่');
    $errorMsg = Yii::t('app', 'กรุณาเลือกเอกสารที่ต้องการดาวน์โหลด');
    $url = Url::current();

    $js = <<<js
    $('body').on('click', '.btn-download-merge', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var keys = $('#crud-datatable-submission-document').yiiGridView('getSelectedRows');
        if (keys.length == 0) {
            dlgError.alert('{$errorMsg}');
            return false;
        }
        dlgPrimary.confirm('{$confirmMsg}', function (result) {
            if (result) { // ok button was pressed
                console.log(keys);
                window.open(url + '&selections=' + JSON.stringify(keys), '_blank');
            } else { // confirmation was cancelled
                console.log('cancel');
            }
        });
        
    });
        
    $('body').on('click', '.btn-assess-form-save', function (e) {
        e.preventDefault();
        $(this).prop('disabled', true);
        $('#submission-type-assess-form').submit();
    });

    $(document).on('pjax:success', '#submission-btn-pjax', function() {
        console.log('success pjax');
        $('.btn-confirm-assess').click();
        $('.btn-assess-form-save').prop('disabled', false);
    })
        
    $('body').on('beforeSubmit', '#submission-type-assess-form', function (e) {
        $(this).prop('disabled', true);
        var form = $(this);
        console.log('before submit');
        var target = document.getElementById('submission-type-assess-form');
        var spinner = new Spinner().spin(target);
        $.ajax({
            url: form.attr("action"),
            type: 'POST',
            data: form.serialize(),
            success: function(data, textStatus, jqXHR) {
                console.log(data);
                if (data.error) {
                    dlgError.alert('<div class="alert alert-danger dark">' + data.message + '</div>');
                    $('.btn-assess-form-save').prop('disabled', false);
                } else {
                    console.log('success');
                    // dlgPrimary.alert('<div class="alert alert-success dark">' + data.message + '</div>');
                    $.pjax.reload({container: data.reload, timeout: false, url: '{$url}', push: false, replace: false});
                }
                
                spinner.stop();
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.alert( textStatus + ": " + jqXHR.status + " " + errorThrown + "</br>" + jqXHR.responseText);
                spinner.stop();
                $('.btn-assess-form-save').prop('disabled', false);
            }
        });
           
        return false;
    });
                
    $(document).on('pjax:complete', '#submission-type-assess-form-pjax', function(){
        $.pjax.reload({container: '#submission-btn-pjax'});
    });
js;
    $this->registerJs($js);
    