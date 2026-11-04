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

    <div class="row">
        <div class="col-md-12 ">
            <div class="alert alert-info alert-dismissible">
                <?=
                Yii::t('app', 'วันที่ประมาณการประชุม : ');
                if (isset($model->meeting_plan_date)) {
                    echo Yii::$app->formatter->format($model->meeting_plan_date, 'date');
                } else {
                    echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                }
                ?>
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
    </div>
    <div class="row">
        <div class="col-md-6">
            <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(app\models\PersonRolePanel::find()->joinWith(['personRole', 'personRole.person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::SECRETARY, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'personRole.person.user_id', 'personRole.person.fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($model, 'secretary_person')->label(Yii::t('app', 'เลือกเลขาที่รับผิดชอบ'))->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => '', 'disabled' => $currentRole['role_id'] != \app\models\Role::STAFF],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
                <div class="col-md-6">
            <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(app\models\PersonRolePanel::find()->joinWith(['personRole', 'personRole.person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::PRESIDENT, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'personRole.person.user_id', 'personRole.person.fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($model, 'president_person')->label(Yii::t('app', 'เลือกประธานเพื่อตรวจสอบหนังสือแจ้งผล'))->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => '', 'disabled' => $currentRole['role_id'] != \app\models\Role::STAFF],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>    
    <?php if($currentRole['role_id'] == \app\models\Role::STAFF){ ?>
    <div class="form-group">
        <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-submission']) ?>
    </div>
    <?php } ?>
    <?php ActiveForm::end(); ?>

</div>
<?php
$MeetingPlanDate = Html::getInputId($model, 'meeting_plan_date');
$MeetingPlanDateVal = isset($model->meeting_plan_date) ? Yii::$app->formatter->asDate($model->meeting_plan_date) : "";
$SendPlanDate = Html::getInputId($model, 'send_plan_date');
$SendPlanDateVal = isset($model->send_plan_date) ? Yii::$app->formatter->asDate($model->send_plan_date) : "";
$js = <<<js
        $('#{$MeetingPlanDate}-disp-kvdate').kvDatepicker('update', '{$MeetingPlanDateVal}');
        $('#{$SendPlanDate}-disp-kvdate').kvDatepicker('update', '{$SendPlanDateVal}');
js;
//$this->registerJs($js);

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