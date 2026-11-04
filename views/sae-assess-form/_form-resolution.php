<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\SaeAssessForm;
use yii\helpers\ArrayHelper;
use app\models\Submission;
use app\models\Resolution;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */
/* @var $form yii\widgets\ActiveForm */
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="sae-assess-form-form">

    <?php
    $form = ActiveForm::begin([
                'layout' => 'inline',
                'id' => 'submission-type-assess-form',
        'enableClientValidation' => false,
                'action' => Url::to(['sae-assess-form/create', 'submissionId' => $model->submission_id, 'submissionCommitteeId' => $model->submission_committee_id]),
    ]);
    ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <div class="padding-top-20">
        <?= Yii::t('app', 'มติของกรรมการ'); ?>
    </div>
    <div>
        <?php
        echo $form->field($model, 'resolution_id', ['options' => ['style' => 'width: 100%;']])->label(false)->radioList(ArrayHelper::map($resolutions, 'id', 'name'), [
            'unselect' => NULL,
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                $id = "resolution_id-{$value}";
                $res = '';
                $style = '';
                $resolution = Resolution::findOne($value);
                $res .= Html::tag('div', Html::radio($name, $checked, [
                                    'id' => $id,
                                    'value' => $value
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "radio-custom radio-primary",
                            'style' => $style,
                ]);
                $res .= '<br>';
                if ($resolution->resolution == Submission::RESOLUTION_C) {
                    $res .= $form->field($model, 'condition', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                    $res .= '<br>';
                } else if ($resolution->resolution == Submission::RESOLUTION_R || $resolution->resolution == Submission::RESOLUTION_N) {
                    $res .= Yii::t('app', 'ในประเด็น') . '<br>';
                    $res .= $form->field($model, 'addition', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                    $res .= '<br>';
                }
                return $res;
            }
        ]);
        ?>
    </div>
    <?php if (($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) || ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $model->submissionCommittee->status == app\models\SubmissionCommittee::STATUS_ACCEPTED)) { ?>
        <div class="form-group margin-15">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-assess-form-save']) ?>
        </div>
        <?php if (isset($model->id) && isset($model->resolution_id)) { ?>
            <a href="<?= Url::to(['sae-assess-form/print-pdf', 'id' => $model->id]) ?>" data-pjax="0" style="text-decoration: none" target="_blank">
                <button type="button" class="btn btn-default"><i class="icon wb-print" aria-hidden="true"></i> <?= Yii::t('app', 'พิมพ์ฟอร์ม') ?></button>
            </a>
        <?php } ?>
    <?php } ?>


    <?php ActiveForm::end(); ?>

</div>
