<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sae-assess-form-search">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['sae-assess-form/index']),
        'method' => 'get',
        'options' => [
            'data-pjax' => 1,
        ],
        'type' => ActiveForm::TYPE_INLINE,
    ]); ?>
    
    <?= $form->field($model, 'submission_id') ?>

    <?= $form->field($model, 'submission_committee_id') ?>

    <?= $form->field($model, 'sae_total') ?>

    <?= $form->field($model, 'sae_for') ?>

    <?= $form->field($model, 'sae_for_fatal') ?>

    <?= $form->field($model, 'sae_dom') ?>

    <?= $form->field($model, 'sae_dom_fatal') ?>

    <?= $form->field($model, 'ec') ?>

    <?= $form->field($model, 'ec_fatal') ?>

    <?= $form->field($model, 'ec_cure') ?>

    <?= $form->field($model, 'ec_not_cure') ?>

    <?= $form->field($model, 'ec_unknown_cure') ?>

    <?= $form->field($model, 'ec_drug') ?>

    <?= $form->field($model, 'ec_not_drug') ?>

    <?= $form->field($model, 'ec_unknown_drug') ?>

    <?= $form->field($model, 'resolution_id') ?>

    <?= $form->field($model, 'suggestion') ?>

    <?= $form->field($model, 'condition') ?>

    <?= $form->field($model, 'addition') ?>

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
