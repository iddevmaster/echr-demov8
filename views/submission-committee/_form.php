<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\SubmissionCommittee */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-committee-form">

    <?php $form = ActiveForm::begin(); ?>


        <?php
        // Usage with ActiveForm and model
        $query = \app\models\CommitteePosition::find()->isDeleted(0)->isCancel(0);
        if ($model->submission->isFromCrec()) {
            $query->andWhere(['id' => \app\models\CommitteePosition::POSITION_LEC]);
        } 
        $data = ArrayHelper::map($query->orderBy('CONVERT(committee_position.name USING TIS620) ASC')->all(), 'id', 'fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
//    exit;
        echo $form->field($model, 'committee_position_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?> 


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
