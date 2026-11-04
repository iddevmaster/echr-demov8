
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
$this->title = Yii::t('app', 'ยื่นโครงการวิจัยที่ผ่านการรับรองแล้ว');
//exit;
?>
<div class="register-transaction-create col-md-12">
    <div class="panel" id="exampleWizardFormContainer">
        <div class="panel-heading">
            <h3 class="panel-title"> </h3>
        </div>
        <div class="panel-body">
            <!-- Steps -->
            <div class="pearls row">
                <?php foreach ($steps as $st): ?>
                    <div class="pearl col-xs-2 <?= \app\controllers\SubmissionController::getStepClass($st, $step) ?>  text-center">
                        <div class="pearl-icon"><i class="<?= $st['icon'] ?>" aria-hidden="true"></i></div>
                        <span class="pearl-title"><?= $st['label'] ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
            <!-- End Steps -->
            <!-- Wizard Content -->
            <?php
            $action = ['submission/new-certified'];
            if (isset($submission->id)) {
                $action['submissionId'] = $submission->id;
                $action['step'] = $step;
            }
//            yii\helpers\VarDumper::dump(Url::to($action));
            $form = ActiveForm::begin([
                        'id' => 'form-submission',
                        'action' => Url::to($action),
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => true,
                        'errorSummaryCssClass' => 'error-summary alert alert-danger dark'
            ]);
            ?>

            <?php
            echo $form->errorSummary([$submissionDoc]);

            echo $form->field($submissionDoc, 'project_id', ['options' => ['class' => 'hidden']])->label(FALSE)->hiddenInput();
            echo $form->field($researcherSearch, 'id', ['options' => ['class' => 'hidden']])->label(FALSE)->hiddenInput();
            ?>

            <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>  
            <div class="wizard-pane <?= $step == \app\controllers\SubmissionController::NEW_CERTIFIED_STEP1 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->render('_new-step1', [
                    'form' => $form,
                    'submissionTypes' => $submissionTypes,
                    'model' => $project,
                    'submission' => $submission,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\SubmissionController::NEW_CERTIFIED_STEP2 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/submission-document/index.php', [
                    'form' => $form,
                    'project' => $project,
                    'submission' => $submission,
//                    'searchModel' => $researcherSearch,
                    'submissionDoc' => $submissionDoc,
                    'dataProvider' => $subDocProvider,
                    'steps' => $steps,
                    'step' => $step,
                    'reloadUrl' => ['submission/new', 'submissionId' => $submission->id, 'step' => $step],
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\SubmissionController::NEW_CERTIFIED_STEP3 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/project-researcher/index.php', [
                    'form' => $form,
                    'project' => $project,
                    'submission' => $submission,
                    'searchModel' => $researcherSearch,
                    'dataProvider' => $researcherProvider,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\SubmissionController::NEW_CERTIFIED_STEP4 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/project-consultant/index.php', [
                    'form' => $form,
                    'project' => $project,
                    'submission' => $submission,
                    'searchModel' => $consultantSearch,
                    'dataProvider' => $consultantProvider,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>            
            <div class="wizard-pane <?= $step == \app\controllers\SubmissionController::NEW_CERTIFIED_STEP5 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->render('submission-summary.php', [
                    'form' => $form,
                    'project' => $project,
                    'submission' => $submission,
//                    'searchModel' => $researcherSearch,
                    'submissionDoc' => $submissionDoc,
                    'dataProvider' => $subDocProvider,
                    'dataProviderre' => $researcherProvider,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>
            <?php ActiveForm::end(); ?>

            <!-- Wizard Content -->
        </div>
    </div>
    <?php
//    $this->render('_form-register', [
//        'model' => $model,
////        'meetingId'=> $meetingId,
//    ])
    ?>
</div>
<?php
$js = <<<js
    $('#ajaxCrudModal').on('click', '.btn-save-researcher', function() {
        $(this).prop('disabled', true);
        var data;

        // Test if browser supports FormData which handles uploads
        if (window.FormData) {
            data = new FormData($('#form-project-researcher')[0]);
        } else {
            // Fallback to serialize
            data = $('#form-project-researcher').serializeArray();
        }
        setTimeout(function() {
            modal.doRemote(
                $('#form-project-researcher').attr('action'),
                $('#form-project-researcher').hasAttr('method') ? $('#form-project-researcher').attr('method') : 'GET',
                data
            );
        }, 200);
        
    });
        
    $('#ajaxCrudModal').on('click', '.btn-save-consultant', function() {
        $(this).prop('disabled', true);
        var data;

        // Test if browser supports FormData which handles uploads
        if (window.FormData) {
            data = new FormData($('#form-project-consultant')[0]);
        } else {
            // Fallback to serialize
            data = $('#form-project-consultant').serializeArray();
        }
        setTimeout(function() {
            modal.doRemote(
                $('#form-project-consultant').attr('action'),
                $('#form-project-consultant').hasAttr('method') ? $('#form-project-consultant').attr('method') : 'GET',
                data
            );
        }, 200);
        
    });
        
js;
$this->registerJs($js);

//$nextStep = $step+1;
//$prevStep = $step-1;
//$prevOpts = ['submission/new', 'step'=>$prevStep];
//$nextOpts = ['submission/new', 'step'=>$nextStep];
//if (isset($submission->id)) {
//    $prevOpts['submissionId'] = $submission->id;
//    $nextOpts['submissionId'] = $submission->id;
//}
//$prevUrl = Url::to($prevOpts);
//$nextUrl = Url::to($nextOpts);
//$js = <<<js
//    $('.btn-next').click(function() {
//        $('#form-submission').attr('action', '{$nextUrl}');
//    });
//    $('.btn-prev').click(function() {
//        $('#form-submission').attr('action', '{$prevUrl}');
//    });
//js;
//$this->registerJs($js);