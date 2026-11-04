<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
?>
<div class="panel">
    <div class="panel-body">
        <div class="row" >
            <div class="col-md-4">
                <h2>เลขที่ HE00111</h2>
                <div class="col-md-8"><p>ชื่อโครงการวิจัยภาษาภาไทย : <?= $model->name ?> </p>
                    <p>ชื่อโครงการวิจัยภาษาไทย : กลไกการเกิดโรคมะเร็งที่เกิดจากการติดเชื้อพยาธิใบไม้ตับในประเทศไทย</p>
                    <p>ชื่อโครงการวิจัยภาษาอังกฤษ : Pathogenesis of liver fluke induced cancer in Thailand</p>
                    <p>ผู้ส่งโครงการวิจัย : นพ.รมณ หาทวายการ</p></div>


            </div>

            <div class="col-md-8 text-right"> 

                <?=
                Html::a('<i class="glyphicon glyphicon-eye-open font-size-18"></i> แสดงข้อมูล', ['bird-information/view'], ['role' => 'modal-remote', 'title' => 'แสดงข้อมูลเพิ่มเติม',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'class' => 'btn btn-primary btn-lg margin-top-5',
//            'data-pjax' => FALSE,
//            'data-request-method' => 'post',
                    'data-toggle' => 'tooltip'])
                ?> 
                <?=
                Html::a('<i class="icon md-device-hub font-size-18"></i> เอกสารประกอบโครงการวิจัย', ['bird-information/ancestor'], ['title' => 'บรรพบุรุษ',
                    'target' => '_blank',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'class' => 'btn btn-primary btn-lg margin-top-5',
                    'data-pjax' => 0,
//            'data-request-method' => 'post',
                    'data-toggle' => 'tooltip'])
                ?> 
                <?=
                Html::a('<i class="icon md-device-hub font-size-18"></i> เลือกกรรมการ/เลขา', ['bird-information/ancestor'], ['title' => 'บรรพบุรุษ',
                    'target' => '_blank',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'class' => 'btn btn-primary btn-lg margin-top-5',
                    'data-pjax' => 0,
//            'data-request-method' => 'post',
                    'data-toggle' => 'tooltip'])
                ?>                 <?=
                Html::a('<i class="icon md-collection-plus font-size-18"></i> บรรจุเข้าสู่วาะการประชุม', ['bird-information/descendants'], ['title' => 'ผลผลิต',
                    'target' => '_blank',
//            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'class' => 'btn btn-primary btn-lg margin-top-5',
                    'data-pjax' => 0,
//            'data-request-method' => 'post',
                    'data-toggle' => 'tooltip'])
                ?> 

                <?=
                Html::a('<i class="glyphicon glyphicon-pencil font-size-18"></i> ประวัติการดำเนินงาน', ['bird-images/index'], ['title' => 'แก้ไข',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'class' => 'btn btn-primary btn-lg margin-top-5',
                    'data-pjax' => FALSE,
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip'])
                ?>

                <?=
                Html::a('<i class="glyphicon glyphicon-trash font-size-18"></i> ลบโครงการวิจัย', ['bird-information/delete'], ['role' => 'modal-remote', 'title' => 'นกตาย',
                    'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    'class' => 'btn btn-primary btn-lg margin-top-5',
                    'data-pjax' => FALSE,
                    'data-request-method' => 'post',
                    'data-toggle' => 'tooltip',
                    'data-confirm-title' => Yii::t('app', 'ยืนยันการตายของนก'),
                    'data-confirm-message' => Yii::t('app', 'ต้องการจัดการรายการนี้ใช่หรือไม่?'),
                    'data-confirm-ok' => Yii::t('app', 'ใช่'),
                    'data-confirm-cancel' => Yii::t('app', 'ไม่')])
                ?>

            </div>
        </div>
    </div></div>
