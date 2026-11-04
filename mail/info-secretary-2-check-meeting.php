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
<p><?= Yii::t('app', 'เรียน') ?> <?= isset($meeting->checkedSecretarySecond->person_id) ? $meeting->checkedSecretarySecond->person->fullName : "" ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', 'แจ้งเรื่องให้เลขาท่านที่ 2 ทำการตรวจสอบวาระการประชุม', [isset($meeting) ? $meeting->fullName : ""]) ?></p>
<p>
    เลขาประจำการประชุมตรวจสอบแล้ว รบกวนให้เลขาท่านที่ 2 ตรวจสอบความถูกต้องด้วยค่ะ
    ในการประชุมครั้งที่ <?= $meeting->fullNameWithDate ?> 
    จึงเรียนมาเพื่อโปรดทราบและดำเนินการตามระบบต่อไป
</p>
<?php
Yii::$app->formatter->locale = $lc;
?>