<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>
    <?= $sc->person->fullName ?> ได้ <?= app\models\SubmissionCommittee::getStatusLabels()[$sc->status] ?> การอ่านงานวิจัย  เรื่อง “<?= $sc->submission->project->name_thai ?>” (<?= $sc->submission->project->name_eng ?>) เลขที่โครงการ <?= $sc->submission->project->project_code ?> (<?= $sc->submission->submissionType->name; ?>)
    และ <?= \app\models\SubmissionCommittee::getStatusLabelsCanMeeting()[$sc->can_meeting] ?> 
</p>
<?php if (isset($sc->remark_meeting)): ?>
<p>
    หมายเหตุ: <?= $sc->remark_meeting ?>
</p>
<?php endif ?>