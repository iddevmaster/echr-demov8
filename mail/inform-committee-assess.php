<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$lc = Yii::$app->formatter->locale;
Yii::$app->formatter->locale = 'th';
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>

<p><?= Yii::t('app', 'เรียน') ?> <?= $sc->person->fullName ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', "ขอติดตามผลการพิจารณาโครงการวิจัย ({$sc->submission->submissionType->name}) {$sc->submission->project->project_code}") ?></p>

<p>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามที่ท่านได้ให้ความอนุเคราะห์รับประเมินโครงการวิจัย เรื่อง <?= $submission->project->name_thai; ?> (<?= $submission->project->name_eng; ?>) เลขที่โครงการ <?= $submission->project->project_code; ?> โดยกำหนดการเข้าประชุมคณะกรรมการจริยธรรมฯ <?= $submission->project->panel->name; ?> ในประมาณการประชุมวันที่ (<?= Yii::$app->formatter->format($submission->meeting_plan_date, 'date'); ?>) นั้น  บัดนี้ใกล้ถึงเวลากำหนดส่งผลแล้ว ศูนย์จริยธรรมฯ จึงขออนุญาตติดตามผลการประเมินโครงการวิจัยดังกล่าว
</p>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ทั้งนี้หากท่านส่งผลมาก่อนได้รับอีเมลนี้แล้ว ขอความกรุณาท่านตรวจสอบการกดยืนยันส่งผลอีกครั้ง หรือแจ้งเจ้าหน้าที่ที่เกี่ยวข้องที่หมายเลขโทรศัพท์ 0897141913 0897141177 
</p>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ขอขอบพระคุณมา ณ ที่นี้
</p>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="color: red">(อีเมลนี้เป็นอีเมลแจ้งเตือนจากระบบอัตโนมัติเมื่อถึงกำหนดส่งผลประเมิน)</font>    
</p>
<?php
Yii::$app->formatter->locale = $lc;
?>