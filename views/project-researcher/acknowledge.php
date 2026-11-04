
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
            <div class="text-center"><?= Html::img('@web/images/logo.png', ['height' => 80]); ?></div>
            <div class="brand-text font-size-18 text-center text-primary"><?= Yii::$app->name ?></div>
            <h1 class="text-center"><?= Yii::t('app', 'ตอบรับการเป็น "ผู้ร่วมวิจัย"') ?></h1>

            <div>
                <?= $message ?>
            </div>
            <br>
        </div>

    </div>

</div>
