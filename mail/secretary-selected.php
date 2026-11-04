<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

$meetingdate = isset($submission->meeting_plan_date) ? \Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:Y') + 543) : "";
$senddate = isset($submission->send_plan_date) ? \Yii::$app->formatter->asDate($submission->send_plan_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->send_plan_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->send_plan_date, 'php:Y') + 543) : "";

?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน อาจารย์ที่เคารพ') ?></p>
<h4><?= Yii::t('app', 'เรื่อง แจ้งขอให้เลือกประเภทการพิจารณาโครงการวิจัย') ?></h4>
<table>
    <tbody>
        <tr>
            <td colspan="4" style="text-indent: 50px;"> <?= Yii::t('app', 'ศูนย์จริยรรมการวิจัยในมนุษย์ ขอความอนุเคราะห์อาจารย์เลือกประเภทการพิจารณาเพื่อขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์') ?>  <?= $submission->submissionType->name ?> <?= Yii::t('app', 'เลขที่') ?> <?= $submission->project->project_code; ?></td>
        </tr>
        <tr>
            <td colspan="4" style="text-indent: 50px;">ทั้งนี้ เพื่อนำเข้าที่ประชุมพิจารณาในวันที่ <?= $meetingdate; ?> </td>
        </tr>

        <tr>
            <td colspan="4" style="text-indent: 50px;"><?= Html::a(Yii::t('app', 'เข้าสู่ระบบเพื่อเลือกประเภทการพิจารณากรุณาคลิกที่นี่'), Url::to(['site/login'], TRUE)) ?> </td>
        </tr>
        <tr>
            <td colspan="4" style="text-indent: 50px;"><font style="color: red"><?= $submission->contactLetter; ?></font></td>
        </tr>
    </tbody>
</table>
