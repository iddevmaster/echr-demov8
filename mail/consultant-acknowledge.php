<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<style>
    table td{
        color:#c55a11;
    }
</style>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo-mail.png', true) ?>"></div>
<div style="background-color: #c55a11;width: auto;text-align: center; height: 50px; padding: 20px;font-size: 30px;"><font style="font-weight: bold; color:#ffffff;">สำหรับการเป็น “ที่ปรึกษา”</font></div>
<font style="font-weight:bold; color: #c55a11;">
<p>เรียน <?= $consultant->person->fullName ?></p>
<h4>มีผู้ยื่นโครงการโดยระบุท่านเป็นที่ปรึกษาโครงการ  โดยมีรายละเอียดโครงการดังนี้</h4>
</font>
<table>
    <thead>
        <tr>
            <th colspan="4"style=" text-align: left;">หัวหน้าโครงการ</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ชื่อ-สกุล </td>
            <td colspan="3"><?= isset($submission->projectLeader) ? $submission->projectLeader->person->fullNameWithEng : "" ?></td>
        </tr>
        <tr>
            <td>สังกัด </td>
            <td colspan="3"><?= isset($submission->projectLeader) ? $submission->projectLeader->person->fullOrg : "" ?></td>
        </tr>
        <tr>
            <td>เบอร์โทรศัพท์ </td>
            <td><?= isset($submission->projectLeader) ? $submission->projectLeader->person->tel : "" ?></td>
            <td>Email </td>
            <td><?= isset($submission->projectLeader) ? $submission->projectLeader->person->email : "" ?></td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th colspan="2" style=" text-align: left;">โครงการ </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ภาษาไทย </td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>ภาษาอังกฤษ </td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>
        <?= Html::a('เข้าไปดูรายละเอียดเอกสารโครงการวิจัยกรุณาคลิกที่นี่', Url::to(['meeting/submission-files', 'submissionId' => $submission->id], TRUE)) ?> <br>
<span><font style="font-weight:bold; color: #c55a11;">ทั้งนี้เมื่อท่านตอบรับแล้ว ท่านจะสามารถติดตามการดำเนินการโครงการได้ในระบบออนไลน์ของศูนย์ฯ ต่อไป</font> </span>
<font style="font-weight:bold; color: #000000;font-size: 28px;"><h4>กรุณาคลิกด้านล่างเพื่อเลือกตอบการเป็นที่ปรึกษาโครงการ</h4></font>
<?= Html::a('<font style="font-weight:bold; color: #4dcc00;">ตกลง</font> ', Url::to(['project-consultant/acknowledge', 'token' => $consultant->ack_token, 'sid' => $submission->id, 'type' => 'accept'], TRUE)) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= Html::a('<font style="font-weight:bold; color: #cc0000;"> ปฎิเสธ</font>', Url::to(['project-consultant/acknowledge', 'token' => $consultant->ack_token, 'sid' => $submission->id, 'type' => 'reject'], TRUE)) ?> <p>
    <font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>
<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= $consultant->person->fullNameEng ?></p>
<font style="font-weight:bold; color: #c55a11;">
<h4>You have been named as a consultant in a research study. Details of the study are listed below</h4>
</font>
<table>
    <thead>
        <tr>
            <th colspan="4" style=" text-align: left;">Name of Principal Investigator</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Affiliation </td>
            <td colspan="3"><?= isset($submission->projectLeader) ? $submission->projectLeader->person->fullNameWithEng : "" ?></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3"><?= isset($submission->projectLeader) ? $submission->projectLeader->person->fullOrg : "" ?></td>
        </tr>
        <tr>
            <td>Telephone number </td>
            <td><?= isset($submission->projectLeader) ? $submission->projectLeader->person->tel : "" ?></td>
            <td>Email </td>
            <td><?= isset($submission->projectLeader) ? $submission->projectLeader->person->email : "" ?></td>
        </tr>
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th colspan="2" style=" text-align: left;">Research Study Title </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Thai </td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>English </td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>
<?= Html::a('Click here to view details of the research study.', Url::to(['meeting/submission-files', 'submissionId' => $submission->id], TRUE)) ?> 
<font style="font-weight:bold; color: #000000;font-size: 28px;"><h4>Do you agree to be a project consonsultant of this study?</h4></font>
<?= Html::a('<font style="font-weight:bold; color: #4dcc00;">Yes</font> ', Url::to(['project-consultant/acknowledge', 'token' => $consultant->ack_token, 'sid' => $submission->id, 'type' => 'accept'], TRUE)) ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= Html::a('<font style="font-weight:bold; color: #cc0000;"> No</font>', Url::to(['project-consultant/acknowledge', 'token' => $consultant->ack_token, 'sid' => $submission->id, 'type' => 'reject'], TRUE)) ?> <p>
<p>
    <font style="color: red"><?= $submission->contactLetterEng; ?></font>    
</p>