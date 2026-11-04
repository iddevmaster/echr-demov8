<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use app\models\MeetingRoom;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Meeting */
/* @var $form yii\widgets\ActiveForm */
$currentRole = Yii::$app->session->get('currentRole');
$meetingNoUrl = yii\helpers\Url::to(['meeting/get-next-meeting-no']);
$elYear = Html::getInputId($model, 'year');
$elMeetingNo = Html::getInputId($model, 'meeting_no');
?>

<div class="meeting-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-meeting'
    ]); ?>

    <div class="row">
        <div class="col-md-4">
            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'panel_id')->widget(Select2::className(), [
                    'data' => $currentRole['panels'],
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        "change" => "function() {
                    var data = $(this).select2('data');
                    if (data.length > 0 && data[0].id) {
                        $.ajax({
                            url: '{$meetingNoUrl}',
                            data: {panelId: data[0].id, year: $('#{$elYear}').val()},
                            method: 'GET',
                            dataType: 'JSON',
                            success: function(res, textStatus, jqXHR) {
                                $('#{$elMeetingNo}').val(res.meetingNo);

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
                            }
                        });
                    }
                }",
                    ]
                ]);
            } else {
                echo $form->field($model, 'panel')->textInput(['readonly' => TRUE, 'value' => $model->panel->name]);
            }
            ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'year')->textInput(['readonly' => TRUE]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'meeting_no')->textInput() ?>
        </div>
    </div>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'meeting_room_id')->widget(Select2::className(), [
        'data' => ArrayHelper::map(MeetingRoom::find()->isDeleted(FALSE)->orderBy('CONVERT(meeting_room.name USING TIS620) ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <div class="row">
        <div class="col-md-6">
            <?=
            $form->field($model, 'start_date')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATETIME
            ])
            ?>
        </div>
        <div class="col-md-6">
            <?=
            $form->field($model, 'end_date')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATETIME
            ])
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
