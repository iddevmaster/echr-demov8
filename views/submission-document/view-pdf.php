<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="submission-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-12">
        <?php
        echo $form->field($model, 'file_name')->widget(FileInput::classname(), [
            'resizeImages' => true,
            'pluginOptions' => [
                'theme' => 'gly',
                // 'uploadUrl' => Url::to(['complaint/upload-file']),
                // 'deleteUrl' => Url::to(['complaint/delete-file']),
                'allowedFileTypes' => ['image', 'video', 'pdf', 'office'],
                // 'allowedFileExtensions' => ["jpg", "png", "gif"],
//                'maxImageWidth' => Yii::$app->params['upload']['image']['maxWidth'],
//                'maxImageHeight' => Yii::$app->params['upload']['image']['maxHeight'],
                'showUpload' => false,
                'showBrowse' => false,
                'showCaption' => false,
                'showClose' => false,
                'showRemove' => false,
                'fileActionSettings' => [
                    'showDrag' => false,
                    'showRemove' => false,
                    'removeClass' => 'd-none',
                ],
                      'initialPreviewShowDelete' => false,

                // 'removeClass' => 'btn btn-danger',
                'browseClass' => '',
                // 'browseIcon' => '<i class="fas fa-camera"></i> ',
                'browseLabel' => '',
//                'initialPreview' => $model->getFileInitialPreview(),
                'initialPreview' => $model->getFileInitialPreview(),
//                
                // 'initialPreview' => [
                //   "https://kartik-v.github.io/bootstrap-fileinput-samples/samples/small.mp4"
                // ],
                'initialPreviewAsData' => true,
                // 'initialCaption' => "The Moon and the Earth",
                'initialPreviewConfig' => [
                    'caption' => $model->file_name,
                ],
                // 'initialPreviewConfig' => [
                //   ['filetype' => 'video/mp4']
                // ],
                'overwriteInitial' => false,
                // 'uploadExtraData' => [
                //     'complaintId' => $model->complaint_id,
                //     'problemId' => $model->problem_id,
                //     'fileType' => $pft->file_type,
                // ]
                'initialPreviewFileType' => 'pdf' //'pdf'
            ],
            'pluginEvents' => [
                // 'change' => "function(ev) {
                //   // console.log($(ev.target).fileinput());
                //   setTimeout(() => {
                //     $('#complaint-file-input').fileinput('upload');
                //   }, 500);
                // }"
                'filebatchselected' => "function(event) {
          console.log(event);
          $('#brand-file-input').fileinput('upload');
        }"
            ],
            'options' => [
                'id' => 'brand-file-input',
                'multiple' => false,
                'accept' => 'image/*,video/*,.pdf,.xlsx,.xls',
            // 'autoOrientImage' => true,
            ]
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
