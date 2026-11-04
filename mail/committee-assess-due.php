<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน อาจารย์ที่เคารพ</p>
<h4>เรื่อง แจ้งครบกำหนดส่งผลประเมินโครงการวิจัย</h4>
<p>
    ตามที่ท่านได้ตอบรับการพิจารณาโครงการ <?= $submission->project->project_code ?> เรื่อง <?= $submission->project->name_thai ?>  ซึ่งกำหนดเข้าประชุมพิจารณาในวันที่ <?= Yii::$app->formatter->format($submission->meeting_plan_date, 'date'); ?> นั้น  บัดนี้ถึงกำหนดส่งผลประเมินโครงการวิจัยแล้ว ศูนย์จริยธรรมฯ ขออนุญาตติดตามผลการประเมินโครงการวิจัยของท่าน
</p>
