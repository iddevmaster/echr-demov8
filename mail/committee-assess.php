<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p style="text-indent: 50px;">
    กรรมการ <?= $model->person->fullName ?> ได้ส่งผลประเมิน (<?= $model->submission->submissionType->name; ?>)
    โครงการวิจัยเพื่อขอรับการพิจารณาด้านจริยธรรมการวิจัยในมนุษย์ เรื่อง “<?= $model->submission->project->name_thai ?>” (<?= $model->submission->project->name_eng ?>) เลขที่โครงการ <?= $model->submission->project->project_code ?> 
    ในระบบเรียบร้อยแล้ว
</p>