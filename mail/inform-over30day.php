<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$lc = Yii::$app->formatter->locale;
Yii::$app->formatter->locale = 'th';
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>

<p><?= Yii::t('app', 'เรียน') ?> <?= $submission->projectLeader->person->fullName ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', "ขอติดตามการแก้ไข/ชี้แจง ภายหลังการประชุมพิจารณาโครงการวิจัยเลขที่โครงการ ({$submission->project->project_code})") ?></p>

<p>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามที่ท่านได้ยื่นเสนอโครงการวิจัยเพื่อขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์ เรื่อง “ <?= $submission->project->name_thai ?> (<?= $submission->project->name_eng ?>)” เลขที่โครงการ <?= $submission->project->project_code ?> นั้น  โดยภายหลังการพิจารณาครั้งแรกคณะกรรมการจริยธรรมได้ส่งหนังสือขอให้ท่านส่งเอกสารฉบับแก้ไขตามข้อเสนอแนะของคณะกรรมการในระบบออนไลน์  (online submission system) ของศูนย์จริยธรรมการวิจัยในมนุษย์ แล้ว
</p>
<p>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เนื่องจากท่านยังไม่ยื่นส่งเอกสารฉบับแก้ไขตามข้อเสนอแนะของคณะกรรมการดังกล่าว และไม่มีการติดต่อกลับมายังศูนย์ฯ ดังนั้นศูนย์ฯ จึงขอให้ท่านดำเนินการส่งเอกสารดังกล่าวและส่งกลับมาที่ศูนย์ฯผ่านระบบ online submission system (https://echr.kku.ac.th) ภายใน 45 วัน นับจากวันที่ประชุมพิจารณาผล  หากเกินกำหนดทางศูนย์ฯ จะ<u>ถอนโครงการ</u>ดังกล่าวออกจากกระบวนการพิจารณาและหากท่านต้องการขอรับการพิจารณาจริยธรรมการวิจัยในโครงการวิจัยดังกล่าวหลังจากนั้น ท่านจะต้องเริ่มใหม่โดยดำเนินการยื่นเสนอขอรับการพิจารณาจริยธรรมการวิจัยสำหรับโครงการใหม่ตั้งแต่ขั้นตอนแรก 
</p>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดทราบและดำเนินการ
</p>
<p>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="color: red">(อีเมลนี้เป็นอีเมลแจ้งเตือนจากระบบอัตโนมัติเมื่อถึงเกินกำหนดส่งแก้ไข)</font>    
</p>
<?php
Yii::$app->formatter->locale = $lc;
?>