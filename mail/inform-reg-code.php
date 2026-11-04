<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน') ?> <?= $person->fullName ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', 'แจ้งได้รับสิทธิ (user) เป็นกรรมการจริยธรรมฯ') ?></p>

<p>ตามที่ท่านได้รับการแต่งตั้งเป็นคณะกรรมการจริยธรรมฯ นั้น บัดนี้ศูนย์จริยธรรมฯ ได้เพิ่มสิทธิ (user) การเป็นกรรมการจริยธรรมฯ ในระบบ Submission Online เรียบร้อยแล้ว ทั้งนี้รหัสสำหรับลงทะเบียนเข้าประชุมของท่านคือ <b><?= $person->reg_code ?></b> ท่านสามารถใช้รหัสนี้ในการลงทะเบียนเข้าประชุมในระบบต่อไป</p>
<p>
     จึงเรียนมาเพื่อโปรดทราบ
</p>
<p>
<font style="color: red"></font>    
</p>
