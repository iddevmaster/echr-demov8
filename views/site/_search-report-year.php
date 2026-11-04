<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Submission;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\models\Role;
use kartik\datecontrol\DateControl;
use app\models\SubmissionTypeGroup;

$currentRole = \Yii::$app->session->get('currentRole');
?>
<div class="submission-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => Url::to(['site/submission-year-report']),
//                'options' => [
//                    'data-pjax' => 1,
//                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>   

    <div class="row">
        <div class="col-md-3"><?=
            $form->field($searchModel, 'startDate')->label(false)->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
            ]);
            ?>
        </div>
        <div class="col-md-3"><?=
            $form->field($searchModel, 'endDate')->label(false)->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
//        'options' => ['placeholder' => ' ถึงวันที่ '],
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?php $datas = ArrayHelper::map(\app\models\PersonRolePanel::find()->joinWith('personRole')->isDeleted(FALSE)->person(\Yii::$app->user->identity->person->id)->all(), 'panel.id', 'panel.i18nName'); ?>

            <?=
            $form->field($searchModel, 'panel_id')->label(FALSE)->widget(Select2::className(), [
                'data' => $datas,
                'options' => ['placeholder' => yii::t('app', 'Panel')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>

        <div class="col-md-1">
            <div class="form-group text-left text-bottom">
                <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>   

    <?php ActiveForm::end(); ?>
</div>