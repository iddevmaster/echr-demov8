<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน เจ้าหน้าที่ประจำโครงการ') ?></p>
<h4><?= Yii::t('app', 'เรื่อง แจ้งเจ้าหน้าที่ประจำโครงการส่งหนังสือแจ้งผลให้นักวิจัย') ?></h4>
<table>
    <tbody>
        <tr>
            <td colspan="4" style="text-indent: 50px;"> <?= Yii::t('app', 'แจ้งเจ้าหน้าที่ประจำโครงการส่งหนังสือแจ้งผลให้นักวิจัย  ') ?>  <?= $submission->submissionType->name ?> <?= Yii::t('app', 'เลขที่') ?> <?= $submission->project->project_code; ?></td>
        </tr>

        <tr>
            <td colspan="4" style="text-indent: 50px;"><?= Html::a(Yii::t('app', 'เข้าสู่ระบบเพื่อส่งหนังสือแจ้งผลให้นักวิจัยคลิกที่นี่'), Url::to(['site/login'], TRUE)) ?> </td>
        </tr>
        <tr>
            <td colspan="4" style="text-indent: 50px;"><font style="color: red"><?= $submission->contactLetter; ?></font></td>
        </tr>
    </tbody>
</table>
