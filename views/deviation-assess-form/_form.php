<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Ethics;
use yii\helpers\ArrayHelper;
use app\models\ReviewChoice;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ContinueAssessForm */
/* @var $form yii\widgets\ActiveForm */
$reviewChoicesByType = ArrayHelper::index($reviewChoices, null, 'type');

$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="continue-assess-form-form">

    <?php
    $form = ActiveForm::begin([
                'id' => 'submission-type-assess-form',
//         'layout' => 'inline',
                'enableClientValidation' => false,
                'action' => Url::to(['deviation-assess-form/create', 'submissionId' => $model->submission_id
                    , 'submissionCommitteeId' => $model->submission_committee_id]),
    ]);
    ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <table class="table table-condensed table-bordered">
        
        <tbody>
                        <tr>
                <td colspan="5" class="text-center font-weight-900" style="background-color: #DCDCDC;"><?= Yii::t('app', 'ข้อสรุปของกรรมการ') ?></td>
            </tr>
            <tr>
                <td colspan="5" class="text-center font-weight-900"><?= $form->field($model, 'suggestion')->label(false)->textarea(['rows' => 4]) ?></td>
            </tr>
            <tr>
                <td colspan="5" class="text-center font-weight-900" style="background-color: #DCDCDC;"><?= Yii::t('app', 'ข้อคิดเห็นของกรรมการ') ?></td>
            </tr>

            <tr>
                <td colspan="5">
                    <?php
                        echo $form->field($model, 'review_choice_id')->label(false)->radioList(ArrayHelper::map($reviewChoicesByType[$model->submission->submission_type_id], 'id', 'name'), [
                            'unselect' => NULL,
                            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                                $id = "review_choice_id-{$value}";
                                $res = '';
                                $style = '';
                                $rc = ReviewChoice::findOne($value);
//                            if ($value == BioForm::TOS_OTHERS || $value == BioForm::TOS_CLINICAL_TRIAL) {
//                                $res .= '<br>';
//                                $label .= "<i class='margin-left-20'>Please specify</i>";
//                                $style = "display: table-cell !important;white-space: nowrap;";
//                            }
                                $res .= Html::tag('div', Html::radio($name, $checked, [
                                                    'id' => $id,
                                                    'value' => $value
                                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                            'class' => "radio-custom radio-primary",
                                            'style' => $style,
                                ]);
                                if ($rc->need_text) {
                                    $res .= $form->field($model, 'review_choice_text', [
                                                'options' => [
//                                                'style' => $style . "width: 100%;"
                                                ]
                                            ])->label(false)->textInput();
                                }
                                if (count($rc->children) > 0) {
                                    $res .= $form->field($model, 'reviewIds', [
                                                'options' => [
                                                    'class' => 'margin-left-20'
//                                                'style' => $style . "width: 100%;"
                                                ]
                                            ])->label(false)->checkboxList(ArrayHelper::map($rc->children, 'id', 'name'), [
                                        'unselect' => NULL,
                                        'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                                            $id = "review-choice-{$value}";
                                            return Html::tag('div', Html::checkbox($name, $checked, [
                                                                'id' => $id,
                                                                'value' => $value,
                                                            ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                                        'class' => "checkbox-custom checkbox-primary"
                                            ]);
                                        }
                                    ]);
                                }
                                return $res;
                            }
                        ]);
                        ?>
                    </td>
                </tr>

        </tbody>
    </table>
    <div>
        <?php if (($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) || ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $model->submissionCommittee->status == app\models\SubmissionCommittee::STATUS_ACCEPTED)) { ?>
            <div class="form-group margin-15 inline">
                <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-assess-form-save']) ?>
            </div>
            <?php if (isset($model->id) && isset($model->resolution_id)) { ?>
                <a href="<?= Url::to(['deviation-assess-form/print-pdf', 'id' => $model->id]) ?>" data-pjax="0" style="text-decoration: none" target="_blank">
                    <button type="button" class="btn btn-default"><i class="icon wb-print" aria-hidden="true"></i> <?= Yii::t('app', 'พิมพ์ฟอร์ม') ?></button>
                </a>
            <?php } ?>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
