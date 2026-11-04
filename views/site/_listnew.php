<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\Submission;
use app\models\Role;

//$statusN = Submission::getStatusLabelsResearcherNew();
$staff = \Yii::$app->user->identity->id;

$currentRole = \Yii::$app->session->get('currentRole');
if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
    $statusN = Submission::getStatusLabelsResearcherNew();
} elseif ($currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::ADMIN || $currentRole['role_id'] == Role::PRESIDENT || $currentRole['role_id'] == Role::SECRETARY) {
    $statusN = Submission::getStatusLabelsStaff();
}
if ($currentRole['role_id'] == Role::STAFF) {
    $user = \Yii::$app->user->identity->id;
} else {
    $user = NULL;
}
$sum = 0;
foreach ($statusN as $s):
    $counts = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $s, null, $panelId, $user);
    $sum += $counts;
endforeach;
?>


<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยใหม่') ?> <span class="badge badge-info size-large"><?= yii::t('app', 'มีทั้งหมด') ?> <?= $sum; ?>  <?= yii::t('app', 'โครงการ') ?></span></h3>
    </div>
    <div class="panel-body">
        <ul class="list-group list-group-full">
            <?php
            foreach ($statusN as $st):
                $fc = null;
                $cc = null;

                $t = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $st, null, $panelId, $user);
//                if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
                if ($t > 0) {
                    $fc = 'red-600 ';
                    $badge = 'badge-success';

                    if ($st == Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN && ($currentRole['role_id'] == Role::PRESIDENT)) {
                        $cc = 'red-800';
                    }
                }
//                }
                ?>
                <li class="list-group-item padding-bottom-0" style="border-top: 1px dashed grey">
                    <span class="pull-right font-size-16"><font class="<?= $fc; ?> bold"><?php
                        $c = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $st, null, $panelId, $user);
                        echo empty($c) ? "0" : $c;
                        ?></font> </span>                                                     
                    <?php
                    if ($st == Submission::STATUS_PRESIDENT_APPROVE_RESULTDOCUMEN && ($currentRole['role_id'] == Role::PRESIDENT)) {
                        $url = ['submission/president-approve-result-documents', 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'panelId' => $panelId];
                        $base64url = base64_encode(\yii\helpers\Url::to($url));
                        $url['url'] = $base64url;
                    } else {
                        $url = ['submission/index', 'status' => $st, 'panelId' => $panelId, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'staff' => $staff];
                        $base64url = base64_encode(\yii\helpers\Url::to($url));
                        $url['url'] = $base64url;
                    }

                    $a = Submission::STATUS_COMMITTEE_ASSESSED ? \Yii::$app->user->identity->getSubmissionAlert(\app\models\SubmissionTypeGroup::GROUP_NEW, $st, null, $panelId, $user) : "";
                    ?></font> </span>                                                         
                        <?php
//                        $url = ['submission/index', 'status' => $st, 'panelId' => $panelId, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'staff' => $staff];
//                        $base64url = base64_encode(\yii\helpers\Url::to($url));
//                        $url['url'] = $base64url;
//                    if ($st == Submission::STATUS_DOC_REJECTED_BY_COMMITTEE) {
//                        $url['staff'] = $staff;
//                    }
                        if ($currentRole['role_id'] == Role::STAFF) {
                            echo Html::a(Submission::getStatusLabelsStaffLables()[$st], $url, [
                                'class' => $cc,
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-toggle' => 'tooltip', 'style' => 'text-decoration: none']);
                        } else {
                            echo Html::a(Submission::getStatusLabels()[$st], $url, [
                                'class' => $cc,
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'data-toggle' => 'tooltip', 'style' => 'text-decoration: none']);
                        }
                        ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>

