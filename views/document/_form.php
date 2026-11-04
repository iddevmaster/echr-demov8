<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Role;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_eng')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'remark_eng')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_report')->radioList([0 => 'ไม่', 1 => 'ใช่']) ?>

    <?php
    // Usage with ActiveForm and model
    $data = ArrayHelper::map(Role::find()->isDeleted(FALSE)->orderBy('CONVERT(role.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    echo $form->field($model, 'role_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'template_file')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        //'multiple' => 'true',
        ],
        'pluginOptions' => [
            'theme' => 'gly',
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreview' => [
                // $model->TemplateFileUrl
                Url::to(['document/download', 'id' => $model->id])
            ],
            'initialPreviewAsData' => true,
            'initialCaption' => $model->template_file,
            'initialPreviewConfig' => [
                    ['caption' => $model->template_file]
            ],
        //'overwriteInitial' => false
        ],
    ]);
    ?>
    <?=
    $form->field($model, 'template_file_eng')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
        //'multiple' => 'true',
        ],
        'pluginOptions' => [
            'theme' => 'gly',
            'showUpload' => false,
            'browseLabel' => '',
            'removeLabel' => '',
            'initialPreview' => [
                // $model->TemplateFileUrlEng
                Url::to(['document/download-eng', 'id' => $model->id])
            ],
            'initialPreviewAsData' => true,
            'initialCaption' => $model->template_file_eng,
            'initialPreviewConfig' => [
                    ['caption' => $model->template_file_eng]
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
