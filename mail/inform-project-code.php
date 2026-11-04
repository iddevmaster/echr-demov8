<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $ld->person->fullNameWithEng ?></p>
<p>เรื่อง แจ้งเลขที่โครงการ</p>
<p>ตามที่ท่านได้ส่งเอกสารโครงการวิจัยเพื่อขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์ เรื่อง (ชื่อภาษาไทย) <?= $submission->project->name_thai ?> (ชื่อภาษาอังกฤษ) <?= $submission->project->name_eng ?> นั้น </p>

<p>บัดนี้ ทางศูนย์จริยธรรมการวิจัยในมนุษย์ ได้รับเอกสารโครงการวิจัยของท่านแล้ว และขอแจ้ง หมายเลขสำคัญประจำโครงการวิจัยของท่าน คือ <?= $submission->project->project_code ?> และเพื่อความสะดวกรวดเร็วในการค้นหา ข้อมูลโครงการวิจัยของท่านขอให้ท่านดำเนินการ ดังนี้</p>

<p>1.  แจ้งหมายเลขสำคัญโครงการ (HE) ทุกครั้งที่มีการติดตามและสอบถามรายละเอียด เกี่ยวกับโครงการวิจัยดังกล่าว</p>
<p>2.  กรณีที่มีการส่งเอกสารใด ๆ เกี่ยวกับโครงการนี้  กรุณาระบุหมายเลขสำคัญโครงการ (HE) ดังกล่าวทุกครั้ง</p>

<p>ศูนย์ฯ ใคร่ขอความร่วมมือท่านปฏิบัติตาม ข้อ 1 และข้อ 2   ไม่เช่นนั้นทางศูนย์ฯ จะต้องใช้เวลาในการสืบค้นหาต้นฉบับหรือ รายละเอียดโครงการ ของท่าน และอาจจะทำให้การพิจารณาโครงการของท่านล่าช้าได้</p>
<p>จึงเรียนมาเพื่อโปรดทราบ และพิจารณาดำเนินการด้วย จะเป็นพระคุณยิ่ง </p>

<p><?= isset($submission->responsiblePerson->person->fullName)? $submission->responsiblePerson->person->fullName:""; ?></p>

<p>
<font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>

<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= $ld->person->fullNameWithEng ?></p>
<p>Subject: Protocol number notification</p>
<p> Referring to your research protocol submitted for ethics in human research approval entitled: (Thai) <?= $submission->project->name_thai ?> (English) <?= $submission->project->name_eng ?> the Center for Ethics in Human research has assigned a research protocol number which is <?= $submission->project->project_code ?> For quick and convenient search for your research protocol, please</p>

<p>1.  Refer to this research protocol number (HE) every time that you follow up on and ask for details about the research protocol.</p>
<p>2.  Refer to this research protocol number (HE) every time that you submit additional documents concerning this research protocol.</p>

<p>The Center seeks your cooperation in complying to our two requests above. Otherwise, it will take time for the Center to retrieve the original manuscript and details of your research protocol, which may result in its delayed consideration. </p>
<p>Thank you for your acknowledgement and compliance.</p>

<p><?= isset($submission->responsiblePerson->person->fullName)? $submission->responsiblePerson->person->fullName:""; ?></p>

<p>
<font style="color: red"><?= $submission->contactLetterEng; ?></font>    
</p>