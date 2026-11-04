<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน  <?= isset($submission->projectLeader->person->fullNameWithEng)? $submission->projectLeader->person->fullNameWithEng : "" ?></p>
<h4>เรื่อง ขอส่งเอกสารคืนเพื่อแก้ไข</h4>

<p>ตามที่ท่านได้ส่งเอกสารเพื่อขอรับการพิจารณาด้านจริยธรรมการวิจัยในมนุษย์ โครงการวิจัย เรื่อง</p>
<table>
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
<p> นั้น จากการตรวจสอบเอกสารเบื้องต้น ขอให้ท่านดำเนินการแก้ไข ทั้งนี้ โปรดเข้าสู่ระบบเพื่อตรวจสอบรายละเอียดที่ให้แก้ไข <?= Html::a('เข้าสู่ระบบเพื่อตรวจสอบข้อมูล', Url::to(['site/login'], TRUE)) ?></p>


<p>
<font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>

<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To  <?= isset($submission->projectLeader->person->fullNameWithEng)? $submission->projectLeader->person->fullNameWithEng : "" ?></p>
<h4>Subject: Correction of Research Study Documents</h4>
<table>
    <thead>
        <tr>
            <th colspan="2">Research Study title</th>
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

<?= Html::a('Log in to check your documents.', Url::to(['site/login'], TRUE)) ?>
<p>
<font style="color: red"><?= $submission->contactLetterEng; ?></font>    
</p>
