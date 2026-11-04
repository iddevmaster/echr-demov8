<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use app\models\Meeting;
use app\models\MeetingAgenda;
use app\models\Person;
use app\models\Role;
use app\models\SubmissionCommittee;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use yii\web\JsExpression;

$searchRole = Role::COMMITTEE;
$currentRole = Yii::$app->session->get('currentRole');
?>
<div class="deviation-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
        // 'action' => Url::to(['report/committee-review-count']),
        'options' => [
            'data-pjax' => 1,
            //                            'target' => '#crud-datatable-ticket-h-pjax'
            // 'class' => 'form-inline'
        ],
        //                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <div class="col-md-3">
            <?=
            $form->field($searchModel, 'startMeetingDate')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($searchModel, 'endMeetingDate')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                //        'options' => ['placeholder' => ' ถึงวันที่ '],
            ]);
            ?>
        </div>
    </div>

    <div class="form-group margin-0">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>