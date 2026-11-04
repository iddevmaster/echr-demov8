<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\ProjectType;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectQuestionChoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-question-choice-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= $form->field($model->projectQuestion, 'name')->textInput(['disabled' => true]) ?>
    
    <?php
    // Usage with ActiveForm and model
    $data = ArrayHelper::map(ProjectType::find()->isDeleted(FALSE)->orderBy('id ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    echo $form->field($model, 'project_type_id')->widget(Select2::classname(), [
        'data' => $data,
//        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>   


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
