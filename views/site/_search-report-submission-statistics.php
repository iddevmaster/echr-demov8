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
use app\models\Agenda;
use kartik\export\ExportMenu;

$currentRole = \Yii::$app->session->get('currentRole');
?>
<div class="submission-search">
    <?php
    $form = ActiveForm::begin([
                'id' => 'search-form',
                'method' => 'get',
                'action' => Url::to(['site/report-submission-statistics']),
//                'options' => [
//                    'data-pjax' => 1,
//                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>   
    <input type="hidden" id="pdf" name="pdf" value="0" />
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
            <?php $datas = ArrayHelper::map(Agenda::find()->isDeleted(false)->isSubmission()->hasParent()->all(), 'id', 'fullName'); ?>
            <?=
            $form->field($searchModel, 'agendaId')->label(false)->widget(Select2::className(), [
                'data' => $datas,
                'options' => ['placeholder' => yii::t('app', 'วาระ')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-2">
            <?php $datas = ArrayHelper::map(\app\models\PersonRolePanel::find()->joinWith('personRole')->isDeleted(FALSE)->person(\Yii::$app->user->identity->person->id)->all(), 'panel.id', 'panel.i18nName'); ?>
            <?=
            $form->field($searchModel, 'panel_id')->label(false)->widget(Select2::className(), [
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
                <?= Html::submitButton('ค้นหา', ['class' => 'btn btn-primary btn-search']) ?>
            </div>
        </div>
    </div>   
    <div>

        <?= Html::button(Yii::t('app', "EXPORT EXCEL"), ['class' => 'btn btn-success pull-right btn-lg btn-excel margin-10']) ?>
        <?= Html::button(Yii::t('app', "EXPORT PDF"), ['name' => 'btn-pdf', 'class' => 'btn btn-default pull-right btn-lg btn-pdf margin-10']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>