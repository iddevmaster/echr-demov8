
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
$this->title = Yii::t('app', 'ยื่นเสนอโครงการวิจัยใหม่');

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
                    <div class="pearl col-xs-3 <?= \app\controllers\ProjectController::getStepClass($st, $step) ?>">
                        <div class="pearl-icon"><i class="<?= $st['icon'] ?>" aria-hidden="true"></i></div>
                        <span class="pearl-title"><?= $st['label'] ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
            <!-- End Steps -->
            <!-- Wizard Content -->
            <?php
            $form = ActiveForm::begin([
                        'id' => 'form-register',
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => true,
            ]);
            ?>
            <input type="hidden" name="step" id="step" value="<?= $step ?>">
            <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>  
            <div class="wizard-pane <?= $step == \app\controllers\ProjectController::REGISTER_STEP1 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/project/_form.php', [
                    'form' => $form,
                    'model' => $project,
                    'submission' => $submission,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\ProjectController::REGISTER_STEP2 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/project-researcher/index.php', [
                    'searchModel' => $presearcherSearch,
                    'dataProvider' => $presearcherProvider,
                    'steps' => $steps,
                    'step' => $step,
                    'id' => $id,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\ProjectController::REGISTER_STEP3 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/document-submission-type/index.php', [
                    'searchModel' => $sdocSearch,
                    'dataProvider' => $sdocProvider,
//                    'docTypes' => $docTypes,
//                    'submission' => $submission,
                    'steps' => $steps,
                    'step' => $step,
//                    'id' => $id,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\ProjectController::REGISTER_STEP4 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/project/_confirm.php', [
                    'regForm' => $project,
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

//$js = <<<js
//    $('.btn-next').click(function() {
//        alert("xxx");
//        $('#form-register').submit(function() {
//            alert("YYY");
//        });
//    });
//js;
//$this->registerJs($js);