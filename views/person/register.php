
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
?>
<br><br>
<div class="register-transaction-create col-md-12 col-md-offset-3">
    <div class="panel" id="exampleWizardFormContainer">
        <div class="panel-heading">
            <div class="text-right">
                <?php
//                $languageItems = Yii::$app->util->getLanguageItems();
//                $languageItem = new \cetver\LanguageSelector\items\DropDownLanguageItem($languageItems);
//                $languageItem = $languageItem->toArray();
//                $languageDropdownItems = \yii\helpers\ArrayHelper::remove($languageItem, 'items');
//                echo \yii\bootstrap\ButtonDropdown::widget([
//                    'label' => $languageItem['label'],
//                    'encodeLabel' => false,
//                    'options' => ['class' => 'btn-inverse'],
//                    'dropdown' => [
//                        'items' => $languageDropdownItems
//                    ]
//                ]);
                ?>
            </div>
            <div class="text-center"><?= Html::img('@web/images/logo.png', ['height' => 80]); ?></div>
            <div class="brand-text font-size-18 text-center text-primary"><?= Yii::$app->name ?></div>
            <h3 class="panel-title text-center"><?= Yii::t('app', 'ลงทะเบียนผู้ใช้งาน') ?></h3>
        </div>
        <div class="panel-body">
            <!-- Steps -->
            <div class="pearls row">
                <?php foreach ($steps as $st): ?>
                    <div class="pearl col-xs-3 <?= \app\controllers\PersonController::getStepClass($st, $step) ?>">
                        <div class="pearl-icon"><i class="<?= $st['icon'] ?>" aria-hidden="true"></i></div>
                        <span class="pearl-title"><?= $st['label'] ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
            <!-- End Steps -->
            <!-- Wizard Content -->
            <?php
            $action = ['person/register'];
            if (isset($profile->id)) {
                $action['personId'] = $profile->id;
                $action['step'] = $step;
            }
            $form = ActiveForm::begin([
                        'id' => 'form-register',
                        'action' => Url::to($action),
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => true,
                        'errorSummaryCssClass' => 'error-summary alert alert-danger dark',
                        'options' => ['enctype' => 'multipart/form-data']
            ]);
            echo $form->errorSummary([$profile]);
            ?>
            <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>  
            <div class="wizard-pane <?= $step == \app\controllers\PersonController::REGISTER_STEP1 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/person/_contact-info.php', [
                    'form' => $form,
                    'model' => $profile,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\PersonController::REGISTER_STEP2 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/person-training/index-register.php', [
                    'form' => $form,
                    'person' => $profile,
                    'dataProvider' => $trainingProvider,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>
            </div>
            <div class="wizard-pane <?= $step == \app\controllers\PersonController::REGISTER_STEP3 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/person/_account-info.php', [
                    'form' => $form,
                    'model' => $regForm,
                    'steps' => $steps,
                    'step' => $step,
                ]);
                ?>

            </div>
            <div class="wizard-pane <?= $step == \app\controllers\PersonController::REGISTER_STEP4 ? '' : 'hidden' ?>" role="tabpanel">
                <?=
                $this->renderFile('@app/views/person/_confirm.php', [
                    'form' => $form,
                    'regForm' => $regForm,
                    'profile' => $profile,
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