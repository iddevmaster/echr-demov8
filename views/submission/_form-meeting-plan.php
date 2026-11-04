<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="submission-form">
    <?php
    $options = [];
    if (isset($action)) {
        $options['action'] = $action;
    }
    $form = ActiveForm::begin($options
//                'id' => 'form-submission',
//        'action' => \yii\helpers\Url::to(['submission/update', 'id' => $model->id])
    );
    ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>


    <?php if (isset($model->meeting_plan_date) && isset($model->send_plan_date)) { ?>
        <?php if (!$model->isFromCrec()) { ?>
            <div class="col-md-6 ">
                <div class="alert alert-info alert-dismissible">
                    <?=
                    Yii::t('app', 'วันที่ประมาณการประชุม : ');
                    if (isset($model->meeting_plan_date)) {
                        echo Yii::$app->formatter->format($model->meeting_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-6 ">
            <div class="alert alert-info alert-dismissible">
                <?=
                Yii::t('app', ' วันที่ประมาณการส่งผลการประเมิน : ');
                if (isset($model->send_plan_date)) {
                    echo Yii::$app->formatter->format($model->send_plan_date, 'date');
                } else {
                    echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการ');
                }
                ?>
            </div>
        </div>
    <?php } else { ?>
        <?php if ($model->isFromCrec()) { ?>
            <div class="alert alert-warning alert-dismissible">
                <?=
                Yii::t('app', ' วันที่ประมาณการส่งผลการประเมินของ CREC : ');
                if (isset($model->crec_send_plan_date)) {
                    echo Yii::$app->formatter->format($model->crec_send_plan_date, 'date');
                } else {
                    echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการของ CREC');
                }
                ?>
            </div>
        <?php } ?>
        <?php if (!$model->isFromCrec()) { ?>
                    <?=
                    $form->field($model, 'meeting_plan_date')->widget(DateControl::classname(), [
                        'type' => DateControl::FORMAT_DATE
                    ]);
                    ?>
        <?php } ?>
                <?=
                $form->field($model, 'send_plan_date')->widget(DateControl::classname(), [
                    'type' => DateControl::FORMAT_DATE
                ]);
                ?>
        <?php
    }
    ?>    
    <div class="form-group">
        <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-submission']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$MeetingPlanDate = Html::getInputId($model, 'meeting_plan_date');
$MeetingPlanDateVal = isset($model->meeting_plan_date) ? Yii::$app->formatter->asDate($model->meeting_plan_date) : "";
$SendPlanDate = Html::getInputId($model, 'send_plan_date');
if (isset($model->crec_send_plan_date) && $model->status == app\models\Submission::STATUS_CODE_GENERATED) {
    $SendPlanDateVal = isset($model->crec_send_plan_date) ? Yii::$app->formatter->asDate($model->crec_send_plan_date) : "";
} else {
    $SendPlanDateVal = isset($model->send_plan_date) ? Yii::$app->formatter->asDate($model->send_plan_date) : "";
}
$js = <<<js
        $('#{$MeetingPlanDate}-disp-kvdate').kvDatepicker('update', '{$MeetingPlanDateVal}');
        $('#{$SendPlanDate}-disp-kvdate').kvDatepicker('update', '{$SendPlanDateVal}');
js;
//    $this->registerJs($js);

$js = <<<js
    $('.btn-save-submission').click(function() {
        var form = $(this).closest('form');
//        alert(form);
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'POST',
            dataType: 'JSON',
            success: function(res, textStatus, jqXHR) {
                if (res.forceReload) {
                    $.pjax.reload(res.forceReload);
                }
                dlgPrimary.alert(res.content);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
            }
        });
    });     
js;
$this->registerJs($js);
?>