<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\ResultDocument */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="result-document-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'resolution')->radioList(app\models\Submission::getResolutionLables()) ?>
    <?= $form->field($model, 'committee_resolution')->textInput(['maxlength' => true]) ?>
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
                // $model->templateFileUrl
                Url::to(['result-document/download', 'id' => $model->id])
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
<?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>




        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

<?php ActiveForm::end(); ?>

</div>
