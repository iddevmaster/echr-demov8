<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Agenda;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meeting-agenda-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php
//    echo $form->field($model, 'description')->widget(CKEditor::className(), [
//        'editorOptions' => [
//            'preset' => 'standard',
//            'inline' => false,
//            'language' => Yii::$app->language,
////            'filebrowserBrowseUrl' => 'browse-images',
////            'filebrowserUploadUrl' => 'upload-images',
////            'extraPlugins' => 'imageuploader',
//        ],
//    ]);
    ?>
    
    <?= $form->field($model, 'need_resolution')->checkbox(); ?>


    <div>
        <?php if (!Yii::$app->request->isAjax) { ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>   
</div>
