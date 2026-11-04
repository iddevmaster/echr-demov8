<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\SubmissionType;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

if($date == 'expire_at'){
    $d1 = 'expire_at1';
    $d2 = 'expire_at2';
}elseif($date == 'next_progress_at'){
    $d1 = 'next_progress_at1';
    $d2 = 'next_progress_at2';
    
}
?>
<div class="project-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'data-pjax' => 0,
//                            'target' => '#crud-datatable-ticket-h-pjax'
//                    'class' => 'form-inline'
                ],
                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <?= $form->field($searchModel, 'name_thai')->textInput([]); ?>
    <?= $form->field($searchModel, 'project_code')->textInput([]); ?>

        <?=
    $form->field($searchModel, $d1)->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'widgetOptions' => [
            'options' => ['placeholder' => 'จากวันที่']
        ]
    ]);
    ?>

    <?=
    $form->field($searchModel, $d2)->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'widgetOptions' => [
            'options' => ['placeholder' => 'ถึงวันที่']
        ]
    ]);
    ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>