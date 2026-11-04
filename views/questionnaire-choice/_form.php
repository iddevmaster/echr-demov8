<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireChoice */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="questionnaire-choice-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>  


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
<script type="text/javascript">
    setTimeout(function () {
        jQuery('#<?= Html::getInputId($model, 'title') ?>').focus();
    }, 500);

</script>