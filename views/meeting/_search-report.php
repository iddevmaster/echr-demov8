<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use kartik\datecontrol\DateControl;
use yii\helpers\ArrayHelper;
use app\models\Panel;
use kartik\select2\Select2;
?>
<div class="meeting-report-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                            'target' => '#crud-datatable-meeting',
//                    'class' => 'form-inline'
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <div class="col-md-4">
            <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(Panel::find()->isDeleted(FALSE)->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($searchModel, 'panel_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?> 
        </div>
        <div class="col-md-4">
            <?=
            $form->field($searchModel, 'start')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($searchModel, 'end')->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE
            ])
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>