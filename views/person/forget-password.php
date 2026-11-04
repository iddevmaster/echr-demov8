
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
<div class="forget-password col-md-12 col-md-offset-3">
    <div class="panel" id="exampleWizardFormContainer">
        <div class="panel-heading">
            <div class="text-center"><?= Html::img('@web/images/logo.png', ['height' => 80]); ?></div>
            <div class="brand-text font-size-18 text-center text-primary"><?= Yii::$app->name ?></div>
            <h3 class="panel-title text-center"><?= Yii::t('app', 'ลืมรหัสผ่าน') ?></h3>
        </div>
        <div class="panel-body">

            <!-- End Steps -->
            <!-- Wizard Content -->
            <?php
            $form = ActiveForm::begin([
                        'id' => 'form-register',
                        'errorSummaryCssClass' => 'error-summary alert alert-danger dark',
            ]);
            echo $form->errorSummary([$model]);
            ?>
            <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>  
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'ส่งข้อมูล'), ['class' => 'btn btn-primary']) ?>
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