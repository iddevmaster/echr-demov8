<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\CommitteeQualification;
/* @var $this yii\web\View */
/* @var $model app\models\PersonRole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-role-form">

<?php $form = ActiveForm::begin(); ?>
    <?php if($model->role_id == app\models\Role::COMMITTEE){ ?>
    <div class="row">
        <div class="col-md-6">
            <?php
            $data = ArrayHelper::map(committeeQualification::find()->isDeleted(FALSE)->orderBy('committee_qualification.id  ASC')->all(), 'id', 'i18nName');
            echo $form->field($modelPerson, 'committee_qualification_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?> 
        </div>
            <div class="col-md-6">
        <?= $form->field($modelPerson, 'gender')->radioList(\app\models\Person::getGenderStatusLabels()); ?>
    </div>
    </div>
    <?php } ?>
    <div class="row">
        <div class="col-md-4">
            <?php
            $data = ArrayHelper::map(app\models\Panel::find()->isDeleted(FALSE)->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'name');
            echo $form->field($model, 'panelIds')->label(FALSE)->checkboxList($data, [
                'unselect' => NULL,
                'separator' => '<br>',
            ]);
            ?>
        </div>
        <div class="col-md-8">
            <?php
            $data = ArrayHelper::map(app\models\Panel::find()->isDeleted(FALSE)->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'name');
            foreach ($data as $i => $d) {
                $data[$i] = Yii::t('app', 'ประจำ');
            }
            echo $form->field($model, 'meetingPanelIds')->label(FALSE)->checkboxList($data, [
                'unselect' => NULL,
                'separator' => '<br>',
            ]);
            ?>
        </div>
    </div>

        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

<?php ActiveForm::end(); ?>
</div>
