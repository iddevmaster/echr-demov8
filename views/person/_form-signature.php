<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bajadev\ckeditor\CKEditor;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Content */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?=
    $form->field($model, 'signature')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        //'multiple' => 'true',
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreview' => false,
            'initialPreviewAsData' => true,
            'initialCaption' => $model->signature,
            'initialPreviewConfig' => [
                    ['caption' => $model->signature]
            ],
        //'overwriteInitial' => false
        ],
    ]);
    ?>
    <?=
    $form->field($model, 'signature_thai')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        //'multiple' => 'true',
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreview' =>false,
            'initialPreviewAsData' => true,
            'initialCaption' => $model->signature_thai,
            'initialPreviewConfig' => [
                    ['caption' => $model->signature_thai]
            ],
        //'overwriteInitial' => false
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