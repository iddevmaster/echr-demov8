<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$lc = Yii::$app->formatter->locale;
Yii::$app->formatter->locale = 'th';
$corDate = Yii::$app->formatter->asDate($submission->correspondence_at, 'php:d F ') . Yii::$app->formatter->asDate($submission->correspondence_at, 'php:Y');

?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน') ?> <?= $submission->projectLeader->person->fullName ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', 'ผลการประเมินด้านจริยธรรมการวิจัยในมนุษย์') ?></p>
<p>
    ตามบันทึกข้อความ เลขที่ <?= $submission->correspondence_no ?> ลงวันที่ <?= $corDate ?> เรื่องขอเสนอโครงการวิจัยภายหลังการประชุมครั้งที่ <?= isset($submission->ref_submission_id) ? $submission->refSubmission->meetingAgenda->meeting->yearNo : "" ?>  สำหรับโครงการวิจัยเพื่อขอรับการพิจารณาด้านจริยธรรมการวิจัยในมนุษย์  เรื่อง “<?= $submission->project->name_thai ?>” (เลขที่โครงการ <?= $submission->project->project_code ?>) ผู้วิจัยได้แก้ไขแล้วเป็นส่วนใหญ่แต่มีบางประเด็นที่ผู้วิจัยควรชี้แจงเพิ่มเติม
จึงเรียนมาเพื่อโปรดทราบเบื้องต้น ทั้งนี้หนังสือแจ้งผลฉบับลงนาม สำนักงานฯ จะขอส่งตามระบบต่อไป
</p>
<p>
<font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>
<?php
Yii::$app->formatter->locale = $lc;
?>