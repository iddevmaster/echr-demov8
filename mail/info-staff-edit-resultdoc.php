<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<h4><?= Yii::t('app', "เรื่อง {$submission->project->project_code} : โปรดดำเนินการแก้ไขหนังสือแจ้งผลพิจารณาตามข้อเสนอแนะประธาน") ?></h4>
<p><?= Yii::t('app', 'เรียน') ?> <?= $submission->responsiblePerson->person->fullName ?></p>
<Br>
<p>ตามที่ได้เสนอร่างหนังสือแจ้งผลพิจารณา </p>
<p>โครงการวิจัย เรื่อง “<?= $submission->project->name_thai ?>”</p>
<p>รหัสโครงการ: <?= $submission->project->project_code ?></p>
<p>ประเภทรายงาน : <?= $submission->submissionType->name; ?></p>
<p>ประธานได้ให้ข้อเสนอแนะเพื่อปรับแก้ ดังนี้ </p>
<p><?= $submission->president_comment; ?></p>

<p>จึงขอความกรุณาดำเนินการปรับแก้ไขให้ถูกต้องครบถ้วน และจัดส่งร่างฉบับแก้ไขกลับมาเพื่อตรวจสอบก่อนนำเสนออีกครั้ง</p>
<br>
<p>ขอแสดงความนับถือ</p>
<p><?= $submission->presidentPerson->person->fullName; ?></p>
<p>ประธานคณะกรรมการ <?= $submission->project->panel->name; ?></p>
