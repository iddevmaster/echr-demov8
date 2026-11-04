<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionResultDocument */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-result-document-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'name')->textInput(); ?>

    <?=
    $form->field($model, 'document_file')->widget(FileInput::classname(), [
        'options' => [
            'accept' => '.doc,.docx,.pdf',
//            'multiple' => 'true',
        ],
        'pluginOptions' => [
            'theme' => 'gly',
            'allowedFileExtensions' => ['doc', 'docx', 'pdf'],
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreviewAsData' => true,
            'initialCaption' => $model->document_file,
            'initialPreviewConfig' => [
                ['caption' => $model->document_file]
            ],
//            'overwriteInitial'=>false
        ],
    ]);
    ?>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
