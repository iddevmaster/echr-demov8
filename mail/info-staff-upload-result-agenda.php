<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$lc = Yii::$app->formatter->locale;
Yii::$app->formatter->locale = 'th';
if (isset($ma)) {
    $meetingDate = Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d F ') . Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:Y');
}
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน') ?> <?= $meeting->checkedStaff->person->fullName ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', 'แจ้งเรื่องให้เจ้าหน้าที่ทำการ Upload ผลการพิจารณาในแต่ละโครงการได้เลย', [isset($meeting) ? $meeting->fullName : ""]) ?></p>
<p>
    เนื่องจากประธานฯประจำการประชุมทำการตรวจสอบผลการถูกต้องของผลการพิจารณาในแต่ละโครงการ ในการประชุมครั้งที่ <?= $meeting->fullNameWithDate ?> เป็นที่เรียบร้อยแล้ว
    จึงเรียนมาเพื่อโปรดทราบและให้เจ้าหน้าที่ดำเนินการ Upload เอกสารแจ้งผลให้นักวิจัย ตามระบบต่อไป
</p>
<?php
Yii::$app->formatter->locale = $lc;
?>