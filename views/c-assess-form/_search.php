<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CAssessForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cassess-form-search">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['cassess-form/index']),
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
        ],
        'type' => ActiveForm::TYPE_INLINE,
    ]); ?>
    
    <?= $form->field($model, 'submission_id') ?>

    <?= $form->field($model, 'submission_committee_id') ?>

    <?= $form->field($model, 'opinion') ?>

    <?= $form->field($model, 'opinion_remark') ?>

    <?= $form->field($model, 'suggestion') ?>

    <?= $form->field($model, 'deleted') ?>

    <?= $form->field($model, 'created_by') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_by') ?>

    <?= $form->field($model, 'updated_at') ?>

  
	<div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>
    
</div>
