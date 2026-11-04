<?php
use kartik\widgets\Select2;

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'เลือกโครงการวิจัยที่ต้องการดาวน์โหลดเอกสาร';
$this->params['breadcrumbs'][] = $this->title;
$data = [
    "HE470506" => "HE470506 : ข้อมูลการติดเชื้อเอชไอวีในเด็กของเอเชีย",
    "HE470505" => "HE470505 : กลไกการเกิดโรคมะเร็งที่เกิดจากการติดเชื้อพยาธิใบไม้ตับในประเทศไทย",
    "HE470511" => "HE470511 : กำหนดเวลาเชิงกลยุทธ์ในการรักษาด้วยยาต้านไวรัสเอชไอวี",

];
?>
<div class="select-project">
    <?= Select2::widget([
    'name' => 'color_2',
    'data' => $data,
    'maintainOrder' => true,
    'options' => ['placeholder' => 'เลือกโครงการวิจัยที่ต้องการดาวน์โหลดเอกสาร'],
    'pluginOptions' => [
        'tags' => true,
        'maximumInputLength' => 10
    ],
]);
?>
</div>
