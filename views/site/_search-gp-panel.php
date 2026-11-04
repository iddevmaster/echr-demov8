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
//                'action' => Url::to(['site/report']),
                'options' => [
                    'data-pjax' => 1,
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>   

    <div class="row">
        <div class="col-md-4"><?=
            $form->field($searchModel, 'startDate')->label(false)->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
            ]);
            ?>
        </div>
        <div class="col-md-4"><?=
            $form->field($searchModel, 'endDate')->label(false)->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
//        'options' => ['placeholder' => ' ถึงวันที่ '],
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