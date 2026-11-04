<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\SubmissionType;
use kartik\select2\Select2;
?>
<div class="project-search margin-bottom-10">
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
    <?= $form->field($searchModel, 'name_thai')->textInput([]); ?>
    <?= $form->field($searchModel, 'project_code')->textInput([]); ?>

    <select name="depart" id="depart" class="form-control">
        <option value="">สถานะโครงการ</option>
        <option value="3.1">งานวิจัยที่ส่งมาให้ประเมินใหม่</option>
        <option value="3.1">งานวิจัยที่ประเมินแล้ว</option>
        <option value="">งานวิจัยที่ยังไม่ประเมิน</option>
        <option value="">งานวิจัยที่ปิดโครงการ</option>


    </select>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>