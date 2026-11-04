<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Vehicle;
use kartik\datecontrol\DateControl;

?>
<div class="vehicle-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
//                            'target' => '#crud-datatable-ticket-h-pjax'
//                    'class' => 'form-inline'
                ],
                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <?= $form->field($searchModel, 'title')->textInput([]); ?>
    <?=
    $form->field($searchModel, 'start_date')->widget(DateControl::className(), [
        'type' => DateControl::FORMAT_DATE,
        'widgetOptions' => [
            'options' => [
                'placeholder' => Yii::t('app', 'วันที่ประชุม'),
            ]
        ],
        
    ]);
//    $form->field($searchModel, 'start_date')->widget(DateRangePicker::className(), [
////                    'hideInput' => TRUE,
//        'options' => [
//            'placeholder' => 'วันที่ลงทะเบียน',
//            'class' => 'form-control'
//        ],
//        'convertFormat' => true,
////            'presetDropdown'=>TRUE,
//        'pluginOptions' => [
//            'singleDatePicker' => true,
//            'autoApply' => TRUE,
//            'showDropdowns' => TRUE,
////            'locale' => [
////                'format' => Yii::$app->params['dateFormat'],
//////                    'separator'=>' to ',
////            ],
//        ]
//    ]);
    ?>


    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>