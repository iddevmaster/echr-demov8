<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\GalleryImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-document-form">

    <?php $form = ActiveForm::begin(); ?>

<?php if (isset($model->document_id)){ ?>
    <?= $form->field($model, 'name')->textInput(['readonly' => TRUE]); ?>
<?php }else{ ?>
    <?= $form->field($model, 'name')->textInput(); ?>
<?php } ?>
    <?=
    $form->field($model, 'file_name')->widget(FileInput::classname(), [
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
            'initialCaption' => $model->file_name,
            'initialPreviewConfig' => [
                ['caption' => $model->file_name]
            ],
//            'overwriteInitial'=>false
        ],
    ]);
    ?>
    <?= $form->field($model, 'remark')->textInput(); ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>