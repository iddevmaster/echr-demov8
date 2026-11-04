<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $ld->person->fullNameWithEng ?></p>
<p>เรื่อง แจ้งเลขที่โครงการวิจัยที่ MOU กับ CREC (<?= isset($submission->project->project_code) ? $submission->project->project_code : "" ?> <?= isset($submission->submission_number) ? ' : ' .$submission->submission_number : "" ?> )</p>
<br>
<p>ตามที่ หัวหน้าโครงการวิจัยหลัก ได้ส่งเอกสารโครงการวิจัยเพื่อขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์ ต่อคณะกรรมการกลางพิจารณาจริยธรรมการวิจัยในคน (CREC) เรื่อง <?= $submission->project->name_thai ?> (<?= $submission->project->name_eng ?>)  เลขที่ CREC No <?= isset($submission->project->crec_number) ? $submission->project->crec_number : "" ?><?= isset($submission->submission_number) ? ' (' .$submission->submission_number .')' : "" ?> โดยมีท่านเป็นหัวหน้าโครงการประจำ site มหาวิทยาลัยขอนแก่น นั้น</p>
<br>
<p>บัดนี้ ทางศูนย์จริยธรรมการวิจัยในมนุษย์ ได้รับเอกสารโครงการวิจัยดังกล่าวแล้ว และขอแจ้งหมายเลขสำคัญประจำโครงการวิจัยของท่าน คือ <?= isset($submission->project->project_code) ? $submission->project->project_code : "" ?>  และเพื่อความสะดวกรวดเร็วในการค้นหา ข้อมูลโครงการวิจัยของท่านขอให้ท่านดำเนินการ ดังนี้</p>
<br>
<p>1. แจ้งหมายเลขสำคัญโครงการ (HE) ทุกครั้งที่มีการติดตามและสอบถามรายละเอียด เกี่ยวกับโครงการวิจัยดังกล่าว</p>
<p>2. กรณีที่มีการส่งเอกสารใด ๆ เกี่ยวกับโครงการนี้ กรุณาระบุหมายเลขสำคัญโครงการ (HE) ดังกล่าวทุกครั้ง</p>
<p><u>3. ตรวจสอบและประสาน การแนบหลักฐานการชำระค่าธรรมเนียม เรียบร้อยแล้ว</u></p>
<br>
<p>ระหว่างรอผลการพิจารณาจากคณะกรรมการกลางฯ นั้น ศูนย์ฯ ใคร่ขอความร่วมมือท่านปฏิบัติตาม ข้อ 1 และข้อ 2 ไม่เช่นนั้นทางศูนย์ฯ จะต้องใช้เวลาในการสืบค้นหาต้นฉบับหรือรายละเอียดโครงการของท่าน และอาจจะทำให้การพิจารณาโครงการของท่านล่าช้าได้</p>
<br>
<p>จึงเรียนมาเพื่อโปรดทราบ และพิจารณาดำเนินการด้วย จะเป็นพระคุณยิ่ง</p>
<p><?= isset($submission->responsiblePerson->person->fullName)? $submission->responsiblePerson->person->fullName:""; ?></p>

<p>
<font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>

