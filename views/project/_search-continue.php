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
        <option value="">เลือกประเภทของโครงการวิจัย</option>
        <option value="3.1">4.1. รายงานความก้าวหน้า </option>
        <option value="">4.2. ขอต่ออายุการรับรอง (Renew) </option>
        <option value="">4.3. ขอปรับปรุงเอกสาร/เพิ่มเติมเอกสาร (Amendment) </option>
        <option value="">4.4. รายงานเหตุการณ์ไม่พึงประสงค์ร้ายแรง (SAE) ในสถาบัน </option>

    </select>
    <select name="depart" id="depart" class="form-control">
        <option value="">สถานะโครงการ</option>
        <option value="3.1">New Submission</option>
        <option value="3.1">งานวิจัยเลยกำหนดการตรวจเอกสาร</option>
        <option value="">งานวิจัยที่ต้องต่ออายุการรับรอง</option>
        <option value="">งานวิจัยที่ปิดโครงการ</option>
        <option value="">งานวิจัยที่ยังไม่ได้เข้าที่ประชุม </option>

    </select>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>