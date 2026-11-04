<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
/* @var $person app\models\Person */
/* @var $training app\models\PersonTraining */
/* @var $daysLeft int|null */

$typeName = isset($training->trainingType) ? $training->trainingType->name : ($training->name_thai_course ?: '-');
$expireText = !empty($training->expire_date) ? Yii::$app->formatter->asDate($training->expire_date) : '-';
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $person->fullName ?></p>
<h4>เรื่อง แจ้งเตือนเอกสารการอบรมใกล้หมดอายุ</h4>
<table>
    <tbody>
        <tr>
            <td>ประเภทการอบรม</td>
            <td><?= Html::encode($typeName) ?></td>
        </tr>
        <tr>
            <td>หลักสูตร</td>
            <td><?= Html::encode($training->name_thai_course) ?></td>
        </tr>
        <tr>
            <td>วันหมดอายุ</td>
            <td><?= $expireText ?></td>
        </tr>
        <?php if (isset($daysLeft)) : ?>
        <tr>
            <td>เหลือเวลาอีก</td>
            <td><?= (int) $daysLeft ?> วัน</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<p>กรุณาดำเนินการอบรมและอัปเดตเอกสารการอบรมก่อนวันหมดอายุ เพื่อให้สามารถเข้าร่วมโครงการวิจัยได้อย่างต่อเนื่อง</p>
<br><?= Html::a('เข้าสู่ระบบเพื่อตรวจสอบข้อมูล', Url::to(['site/login'], TRUE)) ?>

<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= $person->fullNameEng ?></p>
<h4>Subject: Training Certificate Expiry Reminder</h4>
<table>
    <tbody>
        <tr>
            <td>Training type</td>
            <td><?= Html::encode($typeName) ?></td>
        </tr>
        <tr>
            <td>Course</td>
            <td><?= Html::encode($training->name_eng_course ?: $training->name_thai_course) ?></td>
        </tr>
        <tr>
            <td>Expiry date</td>
            <td><?= $expireText ?></td>
        </tr>
        <?php if (isset($daysLeft)) : ?>
        <tr>
            <td>Days remaining</td>
            <td><?= (int) $daysLeft ?> days</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
<p>Please renew your training and update the certificate before the expiry date so you can continue participating in research projects.</p>
<?= Html::a('Log in.', Url::to(['site/login'], TRUE)) ?>
