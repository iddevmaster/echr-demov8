<?php

use yii\widgets\DetailView;
use yii\redactor\widgets\Redactor;
use yii\redactor\widgets\RedactorAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
$this->title = Yii::t('app', 'กำหนดมติการประชุม');
$this->params['breadcrumbs'][] = ['label' => 'จัดการการประชุม', 'url' => ['meeting/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>

<div class="meeting-agenda-view">
    <div class="col-lg-12">
        <div class="panel-body text-right">
            <?= Html::a(Yii::t('app', 'ปิดประชุม'), ['meeting/create'], ['role' => 'modal-remote', 'class' => 'btn btn-warning btn-raised']) ?>                                    

        </div>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-3">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div style="background-color:#fff" id="data_meeting"><table width="100%" border="0" class="table table-hover" id="table_data_chose">
                            <tbody><tr id="table_23" onclick="JavaScript:doAjaxWork(23);">
                                    <td width="19%">3.1.01</td>
                                    <td width="33%">HE591004</td>
                                    <td width="48%">-</td>
                                </tr>
                                <tr id="table_17" onclick="JavaScript:doAjaxWork(17);">
                                    <td width="19%">3.2.01</td>
                                    <td width="33%">HE591004</td>
                                    <td width="48%">-</td>
                                </tr>
                                <tr id="table_21" onclick="JavaScript:doAjaxWork(21);">
                                    <td width="19%">3.4.01</td>
                                    <td width="33%">HE601194</td>
                                    <td width="48%">-</td>
                                </tr>
                                <tr id="table_24" onclick="JavaScript:doAjaxWork(24);">
                                    <td width="19%">3.6.01</td>
                                    <td width="33%">HE601194</td>
                                    <td width="48%">-</td>
                                </tr>
                                <tr id="table_25" onclick="JavaScript:doAjaxWork(25);">
                                    <td width="19%">3.6.02</td>
                                    <td width="33%">HE601194</td>
                                    <td width="48%">-</td>
                                </tr>

                                <tr id="table_20" onclick="JavaScript:doAjaxWork(20);">
                                    <td width="19%">4.7.01</td>
                                    <td width="33%">HE591004</td>
                                    <td width="48%">-</td>
                                </tr>
                            </tbody></table></div>
                </div>
            </div>


        </div>  
        <div class="col-lg-9" id="table_edit">
            <div class="panel panel-default">
                <div class="panel-body" id="work_data">
                    <table width="100%" class="table borderless">
                        <tbody><tr>
                                <td colspan="2" align="center"><p><strong>โครงการที่ขอรับการพิจารณาจริยธรรมในมนุษย์</strong></p>
                                    <p><strong>การประชุม  Panel 1/22 มกราคม 2560 ครั้งที่ 10/2560</strong></p>
                                </td>
                            </tr>
                            <tr>
                                <td ><strong>วาระที่</strong> 4.3.01 โครงการวิจัยใหม่ทางสังคมศาสตร์/มานุษย์วิทยา (Social &amp; Behavioral  Study)</td>
                                <td ><strong>เลขที่โครงการ</strong> HE6001223</td>
                            </tr>

                            <tr>
                                <td colspan="2"><strong>ชื่อโครงการ(ภาษาไทย) </strong>คุณลักษณะสิ่งที่ช่วยสนับสนุนและสิ่งที่เป็นอุปสรรคในการให้และรับข้อมูลป้อนกลับ ของนักศึกษาแพทย์ ในสถาบันการแพทย์ระดับตติยภูมิในภาคตะวันออกเฉียงเหนือ (ทดสอบระบบ)</td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>ชื่อโครงการ(ภาษาอังกฤษ)</strong> The characteristics, supports and barriers of feedback provided to medical students: A mixed method study in a Tertiary care medical institute in Northeastern Thailand</td>
                            </tr>
                            <tr>
                                <td><strong>ชื่อหัวหน้าโครงการ</strong> ผศ.พญ.รสวันต์ อารีมิตร</td>
                                <td><strong>สังกัดหัวหน้าโครงการ </strong>
                                    คณะแพทยศาสตร์      </td>
                            </tr>
 
                            <tr>
                                <td colspan="2"><strong>รายละเอียดโครงการ</strong>
                                    <?=
                                    Redactor::widget([
                                        'model' => $model,
                                        'attribute' => 'parent_id'
                                    ])
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>ผลการประเมินจากกรรมการประเมิน</strong>
                                    <?=
                                    Redactor::widget([
                                        'model' => $model,
                                        'attribute' => 'sort_label'
                                    ])
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>คณะกรรมการพิจารณาในประเด็นดังนี้</strong>
                                    <?=
                                    Redactor::widget([
                                        'model' => $model,
                                        'attribute' => 'description',
                                    ])
                                    ?></td>
                            </tr>
                            <tr>
                                <td colspan="2"><strong>ข้อเสนอแนะ</strong>
                                    <?=
                                    Redactor::widget([
                                        'model' => $model,
                                        'attribute' => 'summary',
                                    ])
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td colspan="2"><p><strong>มติที่ประชุม  </strong>
                                        <select name="resolution_id" id="resolution_id" class="form-control_normal">
                                            <option value="" selected="selected">เลือกมติที่ประชุม</option>
                                            <option value="1">รับรอง (Y)</option><option value="2" selected="">รับรองหลังจากแก้ไขตามข้อเสนอแนะ (C)</option><option value="3">ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนามาพิจารณาใหม่อีกครั้ง (R)</option><option value="5">ไม่รับรอง(N)</option><option value="6">ถอนการรับรอง (W)</option><option value="8">ถอนการพิจารณา (W)</option><option value="7">ยุติการรับรอง (T)</option>      </select>
                                    </p>
                                    <p><strong>รายงานความก้าวหน้า 
                                            <select name="period_report" id="period_report" class="form-control_normal">
                                                <option value="" selected="selected"></option>
                                                <option value="3">3</option>
                                                <option value="6">6</option>
                                                <option value="12" selected="selected">12</option>
                                            </select>
                                            เดือน</strong></p>
                                            <p>จำนวนคณะกรรมการ 18 ท่าน ออกความเห็น 18 ท่าน ไม่ออกความคิดเห็น - ท่าน </p> 
                                </td>
                            </tr>  <tr>
                                <td><input type="hidden" name="research_process_meeting_id" id="research_process_meeting_id" value="16">
                                    <input type="hidden" name="status_start" id="status_start" value="2"></td>
                                <td class="text-right">
                                    <?= Html::a(Yii::t('app', 'บันทึกมติการประชุม'), ['meeting/create'], ['role' => 'modal-remote', 'class' => 'btn btn-primary btn-raised']) ?>                                    
                                    <?= Html::a(Yii::t('app', 'ยกเลิก'), ['meeting/create'], ['role' => 'modal-remote', 'class' => 'btn btn-default btn-raised']) ?>                                    
                                </td>
                            </tr>
                        </tbody></table>
                    <p>Y = รับรอง, C=รับรองหลังจากแก้ไขตามมติที่ประชุม, R=ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำกลับมาพิจารณาใหม่, N = ไม่รับรอง, W=ถอนออกจากการพิจารณาและหรือถอนการรับรอง, T=ยุติการรับรอง </p>
                </div>             
            </div>

        </div>
    </div>
</div>
