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
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?=
        $form->field($model, 'note')->widget(CKEditor::className(), [
            'editorOptions' => [
                'preset' => 'standard',
                'inline' => false,
                'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
            ],
        ]);
        ?>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
