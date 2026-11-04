<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\Submission;
use app\models\Role;

$currentRole = \Yii::$app->session->get('currentRole');
if ($currentRole['role_id'] == Role::RESEARCHER ) {
    $statusN = Submission::getStatusLabelsResearcherContinue();
} elseif ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN) {
    $statusN = Submission::getstatusLabelsStaffContinueNoPanel();
}

$sum = 0;
foreach ($statusN as $s):
    $counts = \Yii::$app->user->identity->getSubmissionNewCount(\app\models\SubmissionTypeGroup::GROUP_CONT, $s);
    $sum += $counts;
endforeach;
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยต่อเนื่อง') ?> <span class="badge badge-info size-large"><?= yii::t('app', 'มีทั้งหมด') ?> <?= $sum; ?>  <?= yii::t('app', 'โครงการ') ?></span></h3>
    </div>
    <div class="panel-body">
        <ul class="list-group list-group-full">
            <?php foreach ($statusN as $st): ?>
                <li class="list-group-item">
                    <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionNewCount(\app\models\SubmissionTypeGroup::GROUP_CONT, $st); ?> <?= yii::t('app', 'โครงการ') ?></span> 
                    <?=
                    Html::a(Submission::getStatusLabels()[$st], ['submission/index', 'status' => $st, 'panelId' => $panelId, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT], [
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-toggle' => 'tooltip'])
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
