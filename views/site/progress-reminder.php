<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน คุณ  <?= $person->fullName ?></p>
<h4>เรื่อง แจ้งเตือนการต่ออายุโครงการ</h4>
<table>
    <tbody>
        <tr>
            <td colspan="2">โครงการ</td>
        </tr>
        <tr>
            <td>ภาษาไทย</td>
            <td colspan="1"><?= $submission->project->name_thai?></td>
        </tr>
        <tr>
            <td>ภาษาอังกฤษ</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
        <tr>
            <td colspan="2">หัวหน้าโครงการ</td>
        </tr>
        <tr>
            <td>ชื่อ-สกุล </td>
            <td colspan="1"><?= isset($submission->project->projectLeader) ? $submission->project->projectLeader->person->fullNameWithEng : "" ?></td>
        </tr>
        <tr>
            <td>สังกัด </td>
            <td colspan="1"><?= isset($submission->project->projectLeader) ? $submission->project->projectLeader->person->fullOrg : "" ?></td>
        </tr>
        <tr>
            <td>วันที่หมดอายุการรับรอง</td>
            <td><?= Yii::$app->formatter->asDate($submission->expire_at) ?></td>
        </tr>
    </tbody>
</table>

<?= Html::a('เข้าสู่ระบบเพื่อตรวจสอบข้อมูล', Url::to(['site/login'], TRUE)) ?>
<p>
<font style="color: red">หมายเหตุ : เป็น​ความอัตโนมัติ​ ส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่โปรดแจ้ง คุณ​สมชาย พาณิชยเวชสันติ เบอร์​โทร​ศัพท์ 089-7141913</font>    
</p>


<p>To <?= $person->fullNameEng ?></p>
<h4>Subject: Research Protocol Renew Reminder Notification</h4>
<table>
    <tbody>
        <tr>
            <td colspan="2">Research title:</td>
        </tr>
        <tr>
            <td>Thai</td>
            <td colspan="1"><?= $submission->project->name_thai?></td>
        </tr>
        <tr>
            <td>English</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
        <tr>
            <td colspan="2">Name of Principal Investigator</td>
        </tr>
        <tr>
            <td>Affiliation </td>
            <td colspan="1"><?= isset($submission->project->projectLeader) ? $submission->project->projectLeader->person->fullNameWithEng : "" ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="1"><?= isset($submission->project->projectLeader) ? $submission->project->projectLeader->person->fullOrg : "" ?></td>
        </tr>
        <tr>
            <td>Expire Date</td>
            <td><?= Yii::$app->formatter->asDate($submission->expire_at) ?></td>
        </tr>
    </tbody>
</table>

<?= Html::a('Log in.', Url::to(['site/login'], TRUE)) ?>
<p>
<font style="color: red">Note: This document is automatically generated. If you want to contact us, please call Mr. Somchai Phanitchayawetsanti at 089-7141913.</font>    
</p>