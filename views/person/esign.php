<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\PersonDocumentAudit;

/* @var $this yii\web\View */
/* @var $person app\models\Person */
/* @var $docType int */
/* @var $refId int|null */
/* @var $otpEnabled bool */

$docLabel = ((int) $docType === PersonDocumentAudit::DOC_TYPE_TRAINING)
        ? Yii::t('app', 'เอกสารการอบรม')
        : Yii::t('app', 'ประวัติผู้วิจัย (CV)');
?>
<div class="person-esign-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'form-person-esign',
                'action' => Url::to(['person/esign', 'personId' => $person->id, 'docType' => $docType, 'refId' => $refId]),
    ]);
    ?>

    <p><b><?= Yii::t('app', 'เอกสาร') ?>:</b> <?= Html::encode($docLabel) ?></p>

    <div class="form-group">
        <label class="checkbox-inline">
            <input type="checkbox" name="apply_stamp" value="1" checked>
            <?= Yii::t('app', 'ประทับชื่อและวันที่ลงบนเอกสาร (ลงนามอิเล็กทรอนิกส์)') ?>
        </label>
    </div>

    <div class="form-group">
        <label class="checkbox-inline">
            <input type="checkbox" name="certify" value="1">
            <?= Html::encode(PersonDocumentAudit::CERTIFY_STATEMENT) ?>
        </label>
    </div>

    <?php if ($otpEnabled) : ?>
        <div class="form-group">
            <label><?= Yii::t('app', 'รหัส OTP') ?></label>
            <div class="input-group">
                <input type="text" name="otp" class="form-control" autocomplete="one-time-code" placeholder="<?= Yii::t('app', 'กรอกรหัส OTP จากอีเมล') ?>">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" id="esign-send-otp"><?= Yii::t('app', 'ส่งรหัส OTP') ?></button>
                </span>
            </div>
            <span id="esign-otp-msg" class="help-block"></span>
        </div>
    <?php else : ?>
        <div class="form-group">
            <label><?= Yii::t('app', 'ยืนยันรหัสผ่านอีกครั้ง') ?></label>
            <input type="password" name="password" class="form-control" autocomplete="current-password">
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>
</div>

<?php if ($otpEnabled) : ?>
<?php
$otpUrl = Url::to(['person/esign-otp']);
$js = <<<JS
$('#esign-send-otp').off('click').on('click', function() {
    var btn = $(this);
    btn.prop('disabled', true);
    $.post('{$otpUrl}', {}, function(res) {
        $('#esign-otp-msg').text(res.message || '');
    }, 'json').always(function() {
        setTimeout(function(){ btn.prop('disabled', false); }, 3000);
    });
});
JS;
$this->registerJs($js);
?>
<?php endif; ?>
