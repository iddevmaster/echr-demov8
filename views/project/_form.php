<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
/* @var $this yii\web\View */
/* @var $model app\models\Project */
/* @var $form yii\widgets\ActiveForm */
?>



<?php if (Yii::$app->user->can(Yii::$app->name . '.meeting.public')): ?>
    <?= $form->field($model, 'panel_id')->radioList([1 => '1', 2 => '2', 3 => '3']) ?>
<?php endif; ?>
<?= $form->field($submission, 'submission_type_id')->dropDownList(ArrayHelper::map(SubmissionType::find()->isDeleted(FALSE)->all(), 'id', 'name')); ?>

<?= $form->field($model, 'name_thai')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'name_eng')->textInput(['maxlength' => true]) ?>

<div class="col-md-6">
    <?= $form->field($model, 'start_date')->textInput() ?></div>

<div class="col-md-6">
    <?= $form->field($model, 'end_date')->textInput() ?></div>
<div class="col-md-6">
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
    ?>      </div>    
<div class="col-md-6">
    <?php
    // Usage with ActiveForm and model
    $data = ArrayHelper::map(FundingSource::find()->isDeleted(FALSE)->orderBy('CONVERT(funding_source.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    echo $form->field($model, 'funding_source_id')->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>      </div>  


<?= $form->field($model, 'funding_source_description')->textInput(['maxlength' => true]) ?>

<div class="col-md-6">        <?= $form->field($model, 'is_child_project')->radioList([0 => 'ไม่', 1 => 'ใช่']) ?>
</div>

<?= $form->field($model, 'progress_period')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>








<div class="form-group">
    <div class="pull-right">
        <?= Html::submitButton('ถัดไป', ['class' => 'btn btn-primary btn-next', 'name' => 'next-step']) ?>
    </div>
</div>


