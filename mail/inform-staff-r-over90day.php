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
<p><?= Yii::t('app', 'เรียน') ?> <?= $submission->responsiblePerson->person->fullName ?></p>
<p><?= Yii::t('app', 'เรื่อง') ?> <?= \Yii::t('app', 'แจ้งให้เจ้าหน้าที่ทำการกำหนดวาระการประชุมให้สำหรับโครงการที่ไม่มีการแก้ไขภายในระยะเวลา 90 วัน (ผลการพิจารณา R)') ?></p>

<table>
    <thead>
        <tr>
            <th colspan="2">เลขที่โครงการ <?= $submission->project->project_code ?></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ชื่อโครงการภาษาไทย</td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>ชื่อโครงการภาษาอังกฤษ</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>
<p>
    เนื่องจากโครงการดังกล่าวนี้ได้ทำการเข้าประชุมและได้รับผลการพิจารณาเป็น "ขอให้ผู้วิจัยชี้แจงเพิ่มเติมเพื่อนำมาพิจารณาใหม่อีกครั้ง" แต่ผู้วิจัยไม่มีการแก้ไขภายในระยะเวลา 90 วัน ตามที่กำหนดไว้ระบบจึงได้ทำการ Submission โครงการดังกล่าวนี้ในประเภท อื่น ๆ เพื่อให้ทางเจ้าหน้าที่ทำการนำไปบรรจุวาระต่อไป
</p>


<?php
Yii::$app->formatter->locale = $lc;
?>