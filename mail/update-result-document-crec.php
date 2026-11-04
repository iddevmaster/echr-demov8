<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>

<p>เรียน เจ้าหน้าที่ดูแลโครงการ</p>
<h4>แจ้งเรื่องได้รับหนังสือแจ้งผลการพิจารณาโครงการ CREC MOU โดยมีรายละเอียดโครงการ ดังนี้</h4>
<table>
    <thead>
        <tr>
            <th colspan="2">โครงการ</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ภาษาไทย</td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>ภาษาอังกฤษ</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
        <tr>
            <td>ประเภทการยื่นโครงการ </td>
            <td colspan="1">(<?= $submission->submissionType->name ?>)</td>
        </tr>
    </tbody>
</table>
<h3 style="text-decoration: underline; color: #f00;">ขอให้ท่านดำเนินการตรวจสอบความถูกต้องและยืนยันการส่งโครงการในระบบต่อไป</h3>
<?= Html::a('เข้าดูรายละเอียดและยืนยันการส่งโครงการวิจัยกรุณาคลิกที่นี่', Url::to(['site/login'], TRUE)) ?>
<p>
    <font style="color: red"><?= $submission->contactLetter; ?></font>
</p>
<br>
<hr color="red" align="center" width="70%" size="5">
<br>