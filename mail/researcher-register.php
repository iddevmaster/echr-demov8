<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<h2><?= Yii::t('app', 'ท่านสามารถเข้าใช้งานระบบ Submission Online ได้ที่ลิงค์ด้านล่าง') ?></h2>
<?= Html::a(Yii::t('app', 'Submission Online'), Url::to($url)); ?><br>
<?= Yii::t('app', 'ชื่อผู้ใช้ของท่านคือ ') ?>: <?= $user->username ?>
<p>
<font style="color: red"><?= Yii::t('app', 'หมายเหตุ : เป็น​ความอัตโนมัติ​ ส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่'); ?></font>    
</p>