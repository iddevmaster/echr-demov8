<?php

use app\models\Submission;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $person->fullName ?></p>
<h4>เรื่อง แจ้งเตือนขอให้เข้าระบบเพื่อยืนยันการส่งโครงการ</h4>


<p>ตามที่ท่านได้เสนอชื่อผู้ร่วมวิจัยโครงการ “<?= $submission->project->name_thai ?>” นั้น บัดนี้ผู้ร่วมวิจัยทุกท่านได้ตอบรับการเข้าร่วมโครงการแล้ว ขอให้ท่านดำเนินการตรวจสอบ/ดำเนินการยืนยันการส่งโครงการ <?= Html::a(Yii::t('app', 'login เพื่อเข้าไปตรวจสอบโครงการของท่าน'), Url::to(['site/login'], TRUE)) ?>   </p>
<p>เพื่อให้เจ้าหน้าที่ศูนย์จริยธรรมฯ ดำเนินการตรวจสอบเอกสารต่อไป ทั้งนี้ หากยังไม่ดำเนินการ จะมีอีเมลแจ้งเตือนท่านทุก 14 วัน</p>

<p>
    <font style="color: red"><?= $submission->contactLetter; ?></font>
</p>