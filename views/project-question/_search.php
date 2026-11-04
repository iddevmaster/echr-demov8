<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectQuestion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-question-search">

    <?php
    $form = ActiveForm::begin([
                'action' => Url::to(['project-question/index']),
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                ],
                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>

    <?= $form->field($model, 'name') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
