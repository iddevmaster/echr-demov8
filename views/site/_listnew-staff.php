<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use app\models\Submission;
$staff = \Yii::$app->user->identity->id;

$statusN = Submission::getStatusLabelsStaff();
$sum = 0;
foreach ($statusN as $s):
    $counts = \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $s, null,$panelId,\Yii::$app->user->identity->person->id);
//echo $counts;
    $sum += $counts;
endforeach;
?>


<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยใหม่') ?> <span class="badge badge-info size-large"><?= yii::t('app', 'มีทั้งหมด')?> <?= $sum; ?>  <?= yii::t('app', 'โครงการ')?></span></h3>
    </div>
    <div class="panel-body">
        <ul class="list-group list-group-full">
            <?php foreach ($statusN as $st): ?>
                <li class="list-group-item">
                    <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, $st, null,$panelId,\Yii::$app->user->identity->person->id); ?>  <?= yii::t('app', 'โครงการ')?></span>                                                     
                    <?=
                    Html::a(Submission::getStatusLabels()[$st], ['submission/index', 'status' => $st,'panelId' => $panelId,'typeGroup'=>\app\models\SubmissionTypeGroup::GROUP_NEW,'staff' => $staff], [
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-toggle' => 'tooltip'])
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </div>
</div>

