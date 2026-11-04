
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
<br><br><br>
<div class="register-transaction-create col-md-12 col-md-offset-3">
    <div class="panel" id="exampleWizardFormContainer">
        <div class="panel-body">
            <div class="text-center"><i class="icon md-check-circle text-success font-size-80"></i></div>
            <h1 class="text-center"><?= Yii::t('app', 'ตั้งรหัสผ่านใหม่เรียบร้อยแล้ว') ?></h1>
            <div class="text-center">
            <?= Html::a('<i class="icon md-lock-open"></i> ' . Yii::t('app', 'เข้าสู่ระบบ'), Url::to(['site/login']), ['class' => 'btn btn-primary btn-lg']) ?>
            </div>
            <br>
        </div>

    </div>

</div>
