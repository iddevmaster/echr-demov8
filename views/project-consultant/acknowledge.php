
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
            <h1 class="text-center"><?= Yii::t('app', 'ตอบรับการเป็น "ที่ปรึกษาโครงการวิจัย"') ?></h1>
            <?php if (isset($projectConsultant)) : ?>
            <?php if ($projectConsultant->acknowledge_status == \app\models\ProjectConsultant::STATUS_ACCEPTED) : ?>
            <div class="alert alert-success dark">
                <?= Yii::t('app', 'ท่านตอบรับการเป็น "ที่ปรึกษาโครงการวิจัย"') ?>
            </div>
            <?php else: ?>
            <div class="alert alert-warning dark">
                <?= Yii::t('app', 'ท่านปฏิเสธการเป็น "ที่ปรึกษาโครงการวิจัย"') ?>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div class="alert alert-danger dark">
                <?= Yii::t('app', 'รหัสตอบรับไม่ถูกต้อง') ?>
            </div>
            <?php endif; ?>
            <br>
        </div>

    </div>

</div>
