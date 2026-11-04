
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
            <h1 class="text-center"><?= Yii::t('app', 'ยืนยันอีเมล') ?></h1>
            <?php if (isset($user)) : ?>
            <div class="alert alert-success dark">
                <?= Yii::t('app', 'อีเมลของท่านได้รับการยืนยันแล้ว') ?>
            </div>
            <div class="text-center">
            <?= Html::a('<i class="icon md-lock-open"></i> ' . Yii::t('app', 'เข้าสู่ระบบ'), Url::to(['site/login']), ['class' => 'btn btn-primary btn-lg']) ?>
            </div>
            <?php else: ?>
            <div class="alert alert-danger dark">
                <?= Yii::t('app', 'รหัสยืนยันไม่ถูกต้อง') ?>
            </div>
            <?php endif; ?>
            <br>
        </div>

    </div>

</div>
