<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\QuestionnaireTitle;
use yii\bootstrap\Tabs;
use app\models\Submission;
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
        <?php \yii\widgets\Pjax::begin(['id' => 'submission-btn-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
        <?php
        ?>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
    <h3><?= $submission->project->project_code . ' : ' . $submission->project->name_thai ?></h3>
    <div class="panel">
        <div class="alert alert-danger">
            <div class="row">
                <?= Yii::t('app', 'หมายเหตุ : ผู้ประเมินจะต้องทำการกรอกแบบฟอร์ม และ อัพโหลดไฟล์ประเมินให้ครบจึงจะสามารถส่งแบบประเมินได้ค่ะ') ?> 
            </div>
        </div>
        <br>
        <div class="panel-body">
            <?= $submission->projectLeader->person->getAlertDeviationProtocolHtml() ?>
            <?php
            //echo Html::errorSummary([$answers]);
            $cm = app\models\SubmissionCommitteeDocument::find()->isDeleted(false)->submissionCommittee($sCommitteeId)->one();
            $upload = '';
            $assessContent = '';
            if ($submission->submissionType->submission_type_group_id == SubmissionTypeGroup::GROUP_NEW) {

                $upload = $this->renderFile('@app/views/submission-committee-document/index-committee.php', [
//                'project' => $project,
                    'submission' => $submission,
                    'submissonDocs' => $submissionDocs,
                    'submissionDoc' => $submissionDoc,
                    'dataProvider' => $subDocProvider,
                    'sCommitteeId' => $sCommitteeId,
                    'reloadUrl' => ['questionnaire-answer/assessment', 'submissionId' => $submission->id, 'sCommitteeId' => $sCommitteeId],
                ]);
            } else {
                if (isset($cm)) {
                    $upload = $this->renderFile('@app/views/submission-committee-document/index-committee.php', [
//                'project' => $project,
                        'submission' => $submission,
                        'submissonDocs' => $submissionDocs,
                        'submissionDoc' => $submissionDoc,
                        'dataProvider' => $subDocProvider,
                        'sCommitteeId' => $sCommitteeId,
                        'reloadUrl' => ['questionnaire-answer/assessment', 'submissionId' => $submission->id, 'sCommitteeId' => $sCommitteeId],
                    ]);
                } else {
                    
                    if (isset($assessForm)) {
                        $assessContent = $this->renderFile('@app/views/questionnaire-answer/assessment-form.php', [
                            'submission' => $submission,
                            'staf' => $staf,
                            'assessForm' => $assessForm,
                            'assessFormParams' => $assessFormParams,
                            'answers' => $answers,
                            'sCommitteeId' => $sCommitteeId,
                        ]);
                    }
                }
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
            $ardProvider = new \yii\data\ArrayDataProvider([
                'allModels' => $submission->getResultDocuments()
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
                    'content' => $submissionDocument . $upload . $assessContent ,
                    'active' => true
                ],
                [
                    'label' => 'รายชื่อนักวิจัย',
                    'content' => $researcher,
                ],
                [
                    'label' => 'หนังสือแจ้งผล',
                    'content' => $submissionResult,
                    'visible' => isset($re)
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
//            $items[] = [
//                'label' => 'แนบไฟล์แบบประเมิน',
//                'content' => $upload,
//            ];
//            }

            echo Tabs::widget([
                'itemOptions' => [
                    'class' => 'padding-top-15'
                ],
                'items' => $items,
            ]);
            ?>
        </div>
    </div></div>
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
        
    $('body').on('beforeSubmit', '#submission-type-assess-form', function (e) {
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
                    dlgError.alert(data.message);
                } else {
                    console.log('success');
                }
                dlgPrimary.alert(data.message);
                spinner.stop();
                $('.btn-assess-form-save').prop('disabled', false);
                $.pjax.reload({container: data.reload, timeout: false, url: '{$url}', push: false, replace: false});
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
