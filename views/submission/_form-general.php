<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
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
    <div class="col-md-12">
        <?= $form->field($project, 'name_thai')->textInput(['maxlength' => true, 'readonly' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($project, 'name_eng')->textInput(['maxlength' => true, 'readonly' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]) ?>
    </div>
    <div class="col-md-12">
        <?php
        $submissionType = ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->group($model->submissionType->submission_type_group_id)->orderBy('CONVERT(submission_type.name USING TIS620) ASC')->all(), 'id', 'name');
        echo $form->field($model, 'submission_type_id')->widget(Select2::classname(), [
            'data' => $submissionType,
            'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?> 
    </div>
    <div class="col-md-12">
        <?= $form->field($model, 'assess_type')->dropDownList(app\models\Submission::getAssessTypeLabel(), []) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($project, 'is_fda')->radioList(Yii::$app->util->getYesNoLabels()); ?>
    </div>
    <div class="col-md-6">
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(FundingSource::find()->isDeleted(FALSE)->orderBy('CONVERT(funding_source.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($project, 'funding_source_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>     
    </div>  
    <div class="col-md-6">
        <?= $form->field($project, 'funding_source_description')->textInput(['maxlength' => true, 'readonly' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]) ?>
    </div>
    <div class="col-md-6">
        <?php if (isset($model->ref_submission_id)): ?>
            <?= $form->field($project, 'is_child_project')->dropDownList([0 => 'ไม่', 1 => 'ใช่'], ['disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]) ?>
        <?php else: ?>
            <?= $form->field($project, 'is_child_project')->radioList([0 => 'ไม่', 1 => 'ใช่'], ['itemOptions' => ['disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]]) ?>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <?= $form->field($project, 'fda_no')->textInput(['maxlength' => true, 'readonly' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'correspondence_no')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-6">
        <?=
        $form->field($model, 'correspondence_at')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE,
//        'disabled' => isset($submission->ref_submission_id)
        ])
        ?>
    </div>


    <div class="col-md-6">        
        <?=
        $form->field($model, 'meeting_plan_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
        ]);
        ?>
    </div>
    <div class="col-md-6"> 
        <?=
        $form->field($model, 'send_plan_date')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE
        ]);
        ?></div>

    <div class="col-md-12 crec" style="<?= isset($model->submissionType) && $model->submission_type_id == SubmissionType::TYPE_CREC ? "" : "display:none" ?>">
        <?= $form->field($project, 'crec_number')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-12">
        <?= $form->field($project, 'remark')->textInput(['maxlength' => true, 'readonly' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))]) ?>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$correspondenceAt = Html::getInputId($model, 'correspondence_at');
$correspondenceAtVal = isset($model->correspondence_at) ? Yii::$app->formatter->asDate($model->correspondence_at) : "";
$MeetingPlanDate = Html::getInputId($model, 'meeting_plan_date');
$MeetingPlanDateVal = isset($model->meeting_plan_date) ? Yii::$app->formatter->asDate($model->meeting_plan_date) : "";
$SendPlanDate = Html::getInputId($model, 'send_plan_date');
$SendPlanDateVal = isset($model->send_plan_date) ? Yii::$app->formatter->asDate($model->send_plan_date) : "";
$js = <<<js
        $('#{$correspondenceAt}-disp-kvdate').kvDatepicker('update', '{$correspondenceAtVal}');
        $('#{$MeetingPlanDate}-disp-kvdate').kvDatepicker('update', '{$MeetingPlanDateVal}');
        $('#{$SendPlanDate}-disp-kvdate').kvDatepicker('update', '{$SendPlanDateVal}');
js;
$this->registerJs($js);
