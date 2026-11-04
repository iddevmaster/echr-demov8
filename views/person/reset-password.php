
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
            <h3 class="panel-title text-center"><?= Yii::t('app', 'ตั้งรหัสผ่านใหม่') ?></h3>
        </div>
        <div class="panel-body">

            <!-- End Steps -->
            <!-- Wizard Content -->
            <?php
            if (isset($model->username)) :
                $form = ActiveForm::begin([
                            'id' => 'form-register',
                            'errorSummaryCssClass' => 'error-summary alert alert-danger dark',
                ]);
                echo $form->errorSummary([$model]);
                ?>
                <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>  
                <?= $form->field($model, 'username')->textInput(['readonly' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true]) ?> 
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            <?php else: ?>
            <div class="alert alert-danger dark"><?= Yii::t('app', 'ลิ้งหมดอายุ') ?></div>
            <?php endif; ?>
            <!-- Wizard Content -->
            <br>
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
