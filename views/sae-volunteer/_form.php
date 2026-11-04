<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SaeVolunteerEthics;

/* @var $this yii\web\View */
/* @var $model app\models\SaeVolunteer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sae-volunteer-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model->submissionVolunteer, 'volunteerCode')->textInput(['disabled' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model->submissionVolunteer, 'typeLabel')->textInput(['disabled' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'dead')->radioList(Yii::$app->util->yesNoLabels) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'cured')->radioList(Yii::$app->util->yesNoUnknownLabels) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'drug')->radioList(Yii::$app->util->yesNoUnknownLabels) ?>
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
        <?php foreach ($saeEthicses as $saeEthics): ?>
            <tr>
                <td>
                    <?= $saeEthics->ethics->name; ?>
                    <?php if ($saeEthics->ethics->need_text): ?>
                        <?= $form->field($saeEthics, "[{$saeEthics->ethics_id}]other")->label(false)->textInput(); ?>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <div class="radio-custom radio-primary">
                        <?=
                        $form->field($saeEthics, "[{$saeEthics->ethics_id}]is_appropriate", [
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
                        $form->field($saeEthics, "[{$saeEthics->ethics_id}]is_appropriate", [
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
                        $form->field($saeEthics, "[{$saeEthics->ethics_id}]is_appropriate", [
                            'template' => "{input}\n<label>{label}</label>\n{hint}\n{error}",
                        ])->radio([
                            'value' => SaeVolunteerEthics::NOT_INVOLVED,
                            'uncheck' => null,
                            'label' => '',
                                ], false);
                        ?>
                    </div>
                </td>
                <td><?= $form->field($saeEthics, "[{$saeEthics->ethics_id}]remark")->label(false)->textInput(); ?></td>
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
