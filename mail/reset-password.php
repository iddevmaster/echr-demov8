<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$url = Url::to(['person/reset-password', 'token' => $user->password_reset_token], TRUE);
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $user->person->fullName ?></p>
<p>โปรดคลิกที่ลิงก์ด้านล่างนี้เพื่อตั้งรหัสผ่านใหม่</p>
<div style="padding: 20px; border-radius: 3px; background-color: #a83b24; color: white"><?= Html::a($url, $url, ['style' => 'color: white']); ?></div>
<p>ในกรณีที่ลิงก์ด้านบนใช้การไม่ได้ สามารถคัดลอกลิงก์ไปวางใน Browser ได้เช่นกัน</p>
<p>
<font style="color: red">หมายเหตุ : เป็น​ความอัตโนมัติ​ ส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่</font>    
</p>
<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>to <?= $user->person->fullNameEng ?></p>
<p>To reset your password, please click the link below.</p>
<div style="padding: 20px; border-radius: 3px; background-color: #a83b24; color: white"><?= Html::a($url, $url, ['style' => 'color: white']); ?></div>
<p>In case the above link does not work, you can copy the link and paste it on your browser.</p>
<p>
<font style="color: red">Note: This document is automatically generated. If you want to contact us</font>    
</p>