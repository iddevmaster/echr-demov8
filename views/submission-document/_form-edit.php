<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\GalleryImage */
/* @var $form yii\widgets\ActiveForm */
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="submission-document-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>


    <?= $form->field($model, 'name')->label(Yii::t('app', 'ชื่อเอกสารภาษาไทย'))->textInput(); ?>

    <?= $form->field($model, 'name_eng')->label(Yii::t('app', 'ชื่อเอกสารภาษาอังกฤษ'))->textInput(); ?>
    <?php if (!isset($model->document) || $model->document->is_report): ?>
        <?= $form->field($model, 'version')->textInput(); ?>
        <?=
        $form->field($model, 'version_at')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
//        'displayFormat' => 'short',
        ]);
        ?>
    <?php endif; ?>
    <?php if (($currentRole['role_id'] == \app\models\Role::ADMIN || $currentRole['role_id'] == \app\models\Role::STAFF || (($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::COORDINATOR) && ($model->submission->status < \app\models\Submission::STATUS_SUBMITTED)))) { ?>
        <?php
        $data = ArrayHelper::map(\app\models\GroupDoc::find()->isDeleted(FALSE)->orderBy('CONVERT(group_doc.name USING TIS620) ASC')->all(), 'id', 'name');
        echo $form->field($model, 'group_doc_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && isset($model->ref_submission_id))],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>

    <?php } ?>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$versionAt = Html::getInputId($model, 'version_at');
$versionAtVal = isset($model->version_at) ? Yii::$app->formatter->asDate($model->version_at) : "";
$js = <<<js
        $('#{$versionAt}-disp-kvdate').kvDatepicker('update', '{$versionAtVal}');
js;
$this->registerJs($js);
