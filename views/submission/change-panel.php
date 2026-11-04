<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>
<div class="submission-change-panel">
    <div class="submission-form">
        <?php
        $options = [];
        if (isset($action)) {
            $options['action'] = $action;
        }
        $form = ActiveForm::begin($options
//                'id' => 'form-submission',
//        'action' => \yii\helpers\Url::to(['submission/update', 'id' => $model->id])
        );
        ?>
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(Panel::find()->isDeleted(FALSE)->andWhere(['not', ['id' => $submission->project->panel_id]])->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'panelId')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?> 
        <?php
        $data = [];
        
        echo $form->field($model, 'responsiblePersonId')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'data' => $data,
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'pluginOptions' => [
                'depends' => [Html::getInputId($model, 'panelId')],
                'url' => Url::to(['/panel/list-responsible-person']),
                'placeholder' => '',
            ],
        ]);
        ?>

        <?php ActiveForm::end(); ?>

    </div>

</div>
