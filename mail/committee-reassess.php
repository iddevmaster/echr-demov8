<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน อาจารย์ที่เคารพ') ?></p>
<h4><?= Yii::t('app', 'เรื่อง แจ้งขอให้ประเมินโครงการวิจัยที่ผู้วิจัยแก้ไขตามข้อเสนอแนะแล้ว') ?></h4>
<table>
    <tbody>
        <tr>
            <td colspan="4" style="text-indent: 50px;"> <?= Yii::t('app', 'ศูนย์จริยรรมการวิจัยในมนุษย์ ขอความอนุเคราะห์อาจารย์ประเมินโครงการวิจัยเพื่อขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์ที่แก้ไขแล้ว') ?>  <?= $submission->submissionType->name ?> <?= Yii::t('app', 'เลขที่โครงการ') ?> <?= $submission->project->project_code; ?></td>
        </tr>
        <tr>
            <td colspan="4" style="text-indent: 50px;"><?= Html::a(Yii::t('app', 'ประเมินโครงการวิจัยกรุณาคลิกที่นี่'), Url::to(['site/login'], TRUE)) ?> </td>
        </tr>
        <tr>
            <td colspan="4" style="text-indent: 50px;"><font style="color: red"><?= $submission->contactLetter; ?></font></td>
        </tr>
    </tbody>
</table>
