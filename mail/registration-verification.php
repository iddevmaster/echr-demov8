<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$url = Url::to(['person/verify-email', 'token' => $user->verify_token], TRUE);
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= isset($user->person->fullName) ? $user->person->fullName : "" ?></p>
<p>ขอบคุณสำหรับการลงทะเบียนระบบ Submission Online โปรดคลิกที่ลิงก์ด้านล่างนี้เพื่อยืนยันอีเมลที่ให้ไว้ จึงจะสามารถเข้าใช้งานระบบได้</p>
<div style="padding: 20px; border-radius: 3px; background-color: #a83b24; color: white"><?= Html::a($url, $url, ['style' => 'color: white']); ?></div>
<p>ในกรณีที่ลิงก์ด้านบนใช้การไม่ได้ สามารถคัดลอกลิงก์ไปวางใน Browser ได้เช่นกัน</p>
<p>
<font style="color: red">หมายเหตุ : เป็น​ความอัตโนมัติ​ ส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่</font>    
</p>
<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= isset($user->person->fullNameEng) ? $user->person->fullNameEng : "" ?></p>
<p>Thank you for registering with our Submission Online. Please click the link below to confirm your email address.</p>
<div style="padding: 20px; border-radius: 3px; background-color: #a83b24; color: white"><?= Html::a($url, $url, ['style' => 'color: white']); ?></div>
<p>In case the above link does not work, you can copy the link and paste it on your browser.</p>
<p>
<font style="color: red">Note: This document is automatically generated. If you want to contact us</font>    
</p>