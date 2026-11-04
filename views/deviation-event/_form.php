<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\DeviationEvent;
use app\models\DeviationEventEthics;
use app\models\SaeVolunteerEthics;

/* @var $this yii\web\View */
/* @var $model app\models\SaeVolunteer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deviation-event-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>
    <h3 class="margin-0"><?= Yii::t('app', 'เหตุการณ์ลำดับที่') ?> <?= $model->submissionEvent->event_no ?></h3>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'is_major_minor_com')->radioList(DeviationEvent::violationLabels()) ?>
        </div>
    </div>

    <table class="table table-bordered table-condensed table-striped">
        <tr>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'ประเด็นการพิจารณาทางด้านจริยธรรม') ?>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'เหมาะสม') ?>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'ไม่เหมาะสม') ?>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'ไม่เกี่ยวข้อง') ?>
            <td class="text-center font-weight-900"><?= Yii::t('app', 'หมายเหตุ') ?>
        </tr>
        <?php foreach ($devEthicses as $devEthics): ?>
            <tr>
                <td>
                    <?= $devEthics->ethics->name; ?>
                    <?php if ($devEthics->ethics->need_text): ?>
                        <?= $form->field($devEthics, "[{$devEthics->ethics_id}]other")->label(false)->textInput(); ?>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <?=
                        $form->field($devEthics, "[{$devEthics->ethics_id}]is_appropriate", [
                            'template' => "{input}\n<label>{label}</label>\n{hint}\n{error}",
                        ])->radio([
                            'value' => SaeVolunteerEthics::APPROPRIATE,
                            'uncheck' => null,
                            'label' => '',
                                ], false);
                        ?>
                    </div>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <?=
                        $form->field($devEthics, "[{$devEthics->ethics_id}]is_appropriate", [
                            'template' => "{input}\n<label>{label}</label>\n{hint}\n{error}",
                        ])->radio([
                            'value' => SaeVolunteerEthics::INAPPROPRIATE,
                            'uncheck' => null,
                            'label' => '',
                                ], false);
                        ?>
                    </div>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <?=
                        $form->field($devEthics, "[{$devEthics->ethics_id}]is_appropriate", [
                            'template' => "{input}\n<label>{label}</label>\n{hint}\n{error}",
                        ])->radio([
                            'value' => SaeVolunteerEthics::NOT_INVOLVED,
                            'uncheck' => null,
                            'label' => '',
                                ], false);
                        ?>
                    </div>
                </td>
                <td><?= $form->field($devEthics, "[{$devEthics->ethics_id}]remark")->label(false)->textInput(); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
