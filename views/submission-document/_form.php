<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\GalleryImage */
/* @var $form yii\widgets\ActiveForm */
?>
<style>
    .input-file-sm {
        height: 20px;
        font-size: 12px;
        padding: 3px 8px;
    }
</style>
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
    <?=
    $form->field($model, 'file_name')->widget(FileInput::classname(), [
        'class' => 'input-file-sm',
        'options' => [
            'accept' => '.doc,.docx,.pdf',
//            'multiple' => 'true',
        ],
        'pluginOptions' => [
            'theme' => 'gly',
            'showPreview' => false,
            'allowedFileExtensions' => ['doc', 'docx', 'pdf'],
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreviewAsData' => true,
            'initialCaption' => $model->file_name,
            'initialPreviewConfig' => [
                ['caption' => $model->file_name]
            ],
//            'overwriteInitial'=>false
        ],
    ]);
    ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>