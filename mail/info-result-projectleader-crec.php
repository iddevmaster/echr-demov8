<?php

use yii\helpers\Html;
use yii\helpers\Url;

$reCrec = isset($submission->crec_resolution) ? \app\models\Submission::getResolutionLables()[$submission->crec_resolution] : "";
$reCrecCerDate = isset($submission->crec_certified_date) ? \Yii::$app->formatter->asDate($submission->crec_certified_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->crec_certified_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->crec_certified_date, 'php:Y') + 543) : "";
$sendRedate = $submission->getStatusDate(app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT);
$reCrecDate = isset($sendRedate) ? \Yii::$app->formatter->asDate($sendRedate, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($sendRedate, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($sendRedate, 'php:Y') + 543) : "";

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน  <?= $submission->projectLeader->person->fullName ?></p>
<h4>เรื่อง แจ้งผลการพิจารณาจริยธรรมการวิจัยในมนุษย์ โครงการวิจัยที่เข้าข่าย MOU กับ CREC</h4>


<br>
<p><font style="color: red">(รายงาน <?= $submission->submissionType->name; ?>)</font></p>
<p>ชื่อโครงการภาษาไทย : <?= $submission->project->name_thai ?></p>
<p>ชื่อโครงการภาษาอังกฤษ : <?= $submission->project->name_eng; ?></p>
<p>เลขที่โครงการ HE : <?= $submission->project->project_code; ?></p>
<p>เลขที่โครงการ CREC : <?= $submission->project->crec_number; ?><?= isset($submission->submission_number) ? ' (' .$submission->submission_number .')' : "" ?></p>
<br>
<p>เนื่องจากโครงการวิจัยดังกล่าว ได้รับการพิจารณาจากคณะกรรมการกลางพิจารณาจริยธรรมการวิจัยในคน (CREC) ผลการพิจารณา “ <?= $reCrec ?> ”  
    <?php if ($submission->submissionType->resolution_label == app\models\SubmissionType::RES_ENDORSE) { ?>
        ณ วันที่ <?= $reCrecCerDate ?>
    <?php } ?>
    เป็นที่เรียบร้อยแล้วนั้น</p>
<br>
<p>บัดนี้คณะกรรมการจริยธรรมการวิจัยในมนุษย์ มหาวิทยาลัยขอนแก่น ได้เห็นชอบตามมติดังกล่าว เรียบร้อยแล้ว 
    <?php if ($submission->resolution == \app\models\Submission::RESOLUTION_Y) { ?>
        <font style="color: blue">ท่านสามารถดำเนินโครงการวิจัยและดาวน์โหลดเอกสาร ตั้งแต่วันที่ <?= $reCrecDate ?> ได้ ทั้งนี้ คณะกรรมการจริยธรรมการวิจัยในมนุษย์ มหาวิทยาลัยขอนแก่น อนุมัติให้ใช้หนังสือรับรอง/รับทราบ ตลอดจนเอกสารฉบับประทับตราที่ออกโดยคณะกรรมการกลางฯ </font>
    <?php } ?>
</p>

<p>
    จึงเรียนมาเพื่อโปรดทราบและดำเนินการ ท่านสามารถเข้าตรวจสอบได้ในระบบ <?= Html::a('คลิกที่นี่', Url::to(['site/login'], TRUE)) ?>
</p>

<p>
    <font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>

