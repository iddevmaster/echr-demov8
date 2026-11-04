<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\Submission;
use app\models\Role;

$resolution = Submission::getStatusLabelsResearcherResolution();
$staff = \Yii::$app->user->identity->id;
$currentRole = \Yii::$app->session->get('currentRole');

if ($currentRole['role_id'] == Role::STAFF) {
    $user = \Yii::$app->user->identity->id;
} else {
    $user = NULL;
}
$sum = 0;
foreach ($resolution as $s):
    $counts = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $s, $panelId, $user);
    $sum += $counts;
endforeach;
?>
<div class="panel panel-warning">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยต่อเนื่องที่ผ่านประชุมคณะกรรมการและได้รับมติแล้ว') ?> <span class="badge badge-info size-large"> <?= yii::t('app', 'มีทั้งหมด') ?> <?= $sum; ?> <?= yii::t('app', 'โครงการ') ?> </span></h3>
    </div>
    <div class="panel-body">
        <ul class="list-group list-group-full">
            <?php
            foreach ($resolution as $re):
                $fc = NULL;
                $t = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $re, $panelId, $user);
//                if ($currentRole['role_id'] == Role::RESEARCHER || $currentRole['role_id'] == Role::COORDINATOR) {
                if ($t > 0) {
                    $fc = 'red-600 ';
                }
//                }
                $url = ['submission/index', 'resolution' => $re, 'panelId' => $panelId, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'staff' => $staff, 'status' => Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT];
                $base64url = base64_encode(\yii\helpers\Url::to($url));
                $url['url'] = $base64url;
                ?>
                <li class="list-group-item padding-bottom-0" style="border-top: 1px dashed grey">
                    <span class="pull-right font-size-16"><font class="<?= $fc; ?> bold"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT, $re, $panelId, $user); ?></font></span>
                        <?=
                        Html::a(Submission::getResolutionLables()[$re], $url, [
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-toggle' => 'tooltip'])
                        ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
