<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\Submission;
use app\models\Role;

$staff = \Yii::$app->user->identity->id;
$currentRole = \Yii::$app->session->get('currentRole');
if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
    $statusN = Submission::getStatusLabelsResearcherContinue();
} elseif ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::PRESIDENT || $currentRole['role_id'] == Role::SECRETARY) {
    $statusN = Submission::getStatusLabelsStaffContinue();
}
if ($currentRole['role_id'] == Role::STAFF) {
    $user = \Yii::$app->user->identity->id;
} else {
    $user = NULL;
}
$sum = 0;
foreach ($statusN as $s):
    $counts = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, $s, NULL, $panelId, $user);
    $sum += $counts;
endforeach;
?>
<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยต่อเนื่อง') ?> <span class="badge badge-info size-large"><?= yii::t('app', 'มีทั้งหมด') ?> <?= $sum; ?>  <?= yii::t('app', 'โครงการ') ?></span></h3>
    </div>
    <div class="panel-body">
        <ul class="list-group list-group-full">
            <?php
            foreach ($statusN as $st):
                $fc = null;
                $cc = null;

                $t = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, $st, NULL, $panelId, $user);
//                if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
                if ($t > 0) {
                    $fc = 'red-600 ';
                    if ($st == Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN && ($currentRole['role_id'] == Role::PRESIDENT)) {
                        $cc = 'red-800';
                    }
                }


                if ($st == Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN && ($currentRole['role_id'] == Role::PRESIDENT)) {
                    $url = ['submission/president-approve-result-documents', 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'panelId' => $panelId];
                    $base64url = base64_encode(\yii\helpers\Url::to($url));
                    $url['url'] = $base64url;
                } else {
                    $url = ['submission/index', 'status' => $st, 'panelId' => $panelId, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'staff' => $staff];
                    $base64url = base64_encode(\yii\helpers\Url::to($url));
                    $url['url'] = $base64url;
                }

//                }
                $a = $st == Submission::STATUS_COMMITTEE_ASSESSED ? \Yii::$app->user->identity->getSubmissionAlert(\app\models\SubmissionTypeGroup::GROUP_CONT, $st, null, $panelId, $user) : "";
                ?>
                <li class="list-group-item padding-bottom-0" style="border-top: 1px dashed grey">
                    <?php if($currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::STAFF){ ?>
                    <span class="pull-right font-size-16"><font class="<?= $fc; ?> bold"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, $st, NULL, $panelId, $user) . $a; ?> </font> </span> 
                    <?php }else{ ?>
                    <span class="pull-right font-size-16"><font class="<?= $fc; ?> bold"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, $st, NULL, $panelId, $user); ?></font> </span> 
                    <?php  } ?>
                        <?=
                        Html::a(Submission::getStatusLabels()[$st], $url, [
                            'class' => $cc,
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-toggle' => 'tooltip'])
                        ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
