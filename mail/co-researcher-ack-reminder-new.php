<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน') ?> <?= $pr->person->fullNameWithEng ?></p>
<p><?= Yii::t('app', 'เรื่อง แจ้งเตือนยังไม่ส่งโครงการ') ?></p>


<p>ตามที่ท่านได้ยื่นเสนอขอรับการพิจารณาโครงการวิจัยเรื่อง "<?= $pr->project->name_thai ?>" นั้น ขณะนี้ยังอยู่ในสถานะ “โครงการที่ยังส่งไม่แล้วเสร็จ” ซึ่งแสดงว่าเป็นโครงการที่คงค้างในระบบ เจ้าหน้าที่ยังไม่รับการส่งของท่าน ขอให้ท่านตรวจสอบ/ดำเนินการ <?= Html::a(Yii::t('app', 'login เพื่อเข้าไปตรวจสอบโครงการของท่าน'), Url::to(['site/login'], TRUE)) ?> </p>
<p>หากตรวจสอบแล้วท่านไม่ประสงค์จะยื่นต่อ ขอให้ลบการยื่นนั้นออกจากระบบ มิเช่นนั้นจะมีอีเมลแจ้งเตือนท่านทุก 14 วัน</p>

<p>
    <font style="color: red">หมายเหตุ : เป็น​ความอัตโนมัติ​ ส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่</font>    
</p>
<hr color="red" align="center" width="70%" size="5">
<p>
    <font style="color: red">Note: This document is automatically generated. If you want to contact us</font>    
</p>