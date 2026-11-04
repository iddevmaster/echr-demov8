<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="register-transaction-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'meeting_id')->textInput() ?>

    <?= $form->field($model, 'person_id')->textInput() ?>

    <?= $form->field($model, 'invited_at')->textInput() ?>

    <?= $form->field($model, 'registered_at')->textInput() ?>

    <?= $form->field($model, 'out_at')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
