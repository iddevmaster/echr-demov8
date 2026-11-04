<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>

<p>เรียน <?= $submission->projectLeader->person->fullNameWithEng ?></p>
<h4>เจ้าหน้าที่ประสานงานโครงการได้ทำการส่งโครงการใหม่ที่ผ่านการรับรองแล้วในนามท่านเพื่อให้เป็นหัวหน้าโครงการ โดยมีรายละเอียดโครงการดังนี้</h4>
<table>
    <thead>
        <tr>
            <th colspan="2">โครงการ</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ภาษาไทย</td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>ภาษาอังกฤษ</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>
<h3 style="text-decoration: underline; color: #f00;">ขอให้ท่านดำเนินการตรวจสอบความถูกต้องและยืนยันการส่งโครงการในระบบต่อไป</h3>
        <?= Html::a('เข้าดูรายละเอียดและยืนยันการส่งโครงการวิจัยกรุณาคลิกที่นี่', Url::to(['site/login'], TRUE)) ?> 
<p>
<font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>
<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= $submission->projectLeader->person->fullNameWithEng ?></p>
<h4>A research protocol has been submitted by a research coordinator. You are named as the principal investigator of the protocol. Details of the study are listed below.</h4>
<table>
    <thead>
        <tr>
            <th colspan="2">Research Title:</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Thai</td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>English</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>
<h3 style="text-decoration: underline; color: #f00;">Please verify the protocol and confirm your protocol submission.</h3>
<?= Html::a('Click here to verify the protocol and confirm your submission', Url::to(['site/login'], TRUE)) ?> 
<p>
<font style="color: red"><?= $submission->contactLetterEng; ?></font>    
</p>