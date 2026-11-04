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

$currentRole = \Yii::$app->session->get('currentRole');
?>
<div class="submission-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => Url::to(['site/report-new-monthly']),
                'options' => [
                    'data-pjax' => 1,
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>   

    <div class="row">
        <div class="col-md-2">
            <?=
            $form->field($searchModel, 'statusYear')->label(false)->widget(Select2::classname(), [
                'data' => Yii::$app->util->selectYears,
                'options' => ['placeholder' => Yii::t('app', 'เลือกปีเพื่อทำการแสดงข้อมูล')],
                'pluginOptions' => [
                    'allowClear' => true,
                    'width' => '250px',
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
        <div class="col-md-2">
            <div class="form-group text-left text-bottom">
                <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>   

    <?php ActiveForm::end(); ?>
</div>