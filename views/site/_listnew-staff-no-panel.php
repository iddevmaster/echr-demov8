<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\Submission;

$staff = \Yii::$app->user->identity->id;

$statusN = Submission::getStatusLabelsStaffNoPanel();
$sum = 0;
foreach ($statusN as $s):
    if ($s == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE || $s == Submission::STATUS_SUBMITTED) {
        $counts = \Yii::$app->user->identity->getSubmissionNewPanelCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $s, $staff);
    } else {
        $counts = \Yii::$app->user->identity->getSubmissionNewPanelCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $s);
    }
//echo $counts;
    $sum += $counts;
endforeach;
?>


<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยใหม่') ?> <span class="badge badge-info size-large"><?= yii::t('app', 'มีทั้งหมด') ?> <?= $sum; ?>  <?= yii::t('app', 'โครงการ') ?></span></h3>
    </div>
    <div class="panel-body">
        <ul class="list-group list-group-full">
                <?php foreach ($statusN as $st): ?>
                <li class="list-group-item">
                    <?php if ($st == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE || $st == Submission::STATUS_SUBMITTED) { ?>
                        <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionNewPanelCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $st, $staff); ?>  <?= yii::t('app', 'โครงการ') ?></span>                                                     
                    <?php } else { ?>
                        <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionNewPanelCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $st); ?>  <?= yii::t('app', 'โครงการ') ?></span>                                                     
                    <?php } ?>
                    <?php
                    $url = ['submission/index', 'status' => $st, 'panelId' => $panelId, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW];
                    $base64url = base64_encode(\yii\helpers\Url::to($url));
                    $url['url'] = $base64url;
                    
                    if ($st == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE || ($st == Submission::STATUS_SUBMITTED)) {
                        $url['staff'] = $staff;
                    }
                    echo Html::a(Submission::getStatusLabelsStaffNoPanelLables()[$st], $url, [
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-toggle' => 'tooltip'])
                    ?>
                </li>
<?php endforeach; ?>
        </ul>

    </div>
</div>

