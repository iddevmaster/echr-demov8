<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionDocument */
?>
<div class="submission-document-create">
    <div class="submission-document-form">

        <?php $form = ActiveForm::begin(); ?>
        
        <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

        <?= $form->field($model, 'name')->textInput(['disabled' => true]); ?>
        <?= $form->field($model, 'name_eng')->textInput(); ?>
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
        $form->field($model, 'groupDocumentId')->widget(Select2::classname(), [
            'data' => ArrayHelper::map($model->getAvailableGroupDocuments(), 'id', 'nameSubmissionType'),
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
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
</div>
