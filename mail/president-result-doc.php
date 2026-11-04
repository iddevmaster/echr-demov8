<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$edit = isset($submission->president_comment) ? '(ฉบับแก้ไข)' : "";
if (isset($submission->president_person)) {
    $namePresident = $submission->presidentPerson->person->fullName;
} else {
    $namePresident = $submission->project->panel->chairman->fullName;
}

?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<h4><?= \Yii::t('app', 'เรื่อง ขอความอนุเคราะห์ลงนามหนังสือแจ้งผลพิจารณาโครงการวิจัย [{1}] [{0}] {2} ', [$submission->submissionType->name, $submission->project->project_code, $edit]); ?></h4>
<p><?= Yii::t('app', 'เรียน ') ?>  <?= $namePresident; ?></p>
<br>
<?php if (isset($submission->president_comment)) { ?>
    <p>ตามที่สำนักงานได้ปรับแก้หนังสือแจ้งผลพิจารณาตามข้อเสนอแนะเรียบร้อยแล้ว</p>
    <p>โครงการวิจัย เรื่อง “<?= $submission->project->name_thai ?>”</p>
<?php } else { ?>
    <p>ตามที่ <?= $submission->projectLeader->person->fullName ?>  ได้ส่ง <?= $submission->submissionType->name; ?>  ของโครงการวิจัย เรื่อง “<?= $submission->project->name_thai ?>”</p>
<?php } ?>
<p>รหัสโครงการ: <?= $submission->project->project_code ?></p>
<?php if (isset($submission->president_comment)) { ?>
    <p>จึงใคร่ขอความกรุณาท่านประธานโปรดพิจารณาลงนามในหนังสือฉบับแก้ไข เพื่อดำเนินการในขั้นตอนถัดไป</p>
<?php } else { ?>
    <p>บัดนี้ กรรมการผู้ทบทวนได้พิจารณาเรียบร้อยแล้ว สำนักงานจึงใคร่ขอความกรุณาท่านประธานโปรดพิจารณาลงนามในหนังสือแจ้งผลพิจารณา เพื่อดำเนินการในขั้นตอนถัดไป</p>
<?php } ?>
<p>จึงเรียนมาเพื่อโปรดพิจารณา และขอขอบพระคุณเป็นอย่างสูง</p>
<br>
<p>ขอแสดงความนับถือ</p>

<P><font style="color: red"><?= $submission->contactLetter; ?></font></P>



