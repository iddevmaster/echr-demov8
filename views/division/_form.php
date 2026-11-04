<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Organization;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Division */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="division-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>


        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(Organization::find()->isDeleted(FALSE)->orderBy('CONVERT(organization.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'organization_id')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>  
        <?php
        $data = [];
        if (!empty($model->organization_id)) {
            $data = ArrayHelper::map(\app\models\Department::find()->isDeleted(FALSE)->organization($model->organization_id)->orderBy('CONVERT(department.name USING TIS620)')->all(), 'id', 'name');
            // \yii\helpers\VarDumper::dump($data, 10, TRUE);
        }
        echo $form->field($model, 'department_id')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'data' => $data,
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'pluginOptions' => [
                'depends' => [Html::getInputId($model, 'organization_id')],
                'url' => Url::to(['/department/list']),
                'placeholder' => '',
            ],

        ]);
        ?> 
      <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_eng')->textInput(['maxlength' => true]) ?>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
