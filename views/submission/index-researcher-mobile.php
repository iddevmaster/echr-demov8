<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Submission;
use app\models\SubmissionCommittee;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'รายการส่งโครงการวิจัย');
//$this->params['breadcrumbs'][] = $this->title;

if (isset($status)) {
    $st = Submission::getStatusLabels()[$status];
} elseif (isset($resolution)) {
    $st = Submission::getResolutionLables()[$resolution];
}
//CrudAsset::register($this);

$currentRole = Yii::$app->session->get('currentRole');
?>
<?php Pjax::begin(['id' => 'crud-datatable-submission-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
<div class="submission-index ">
    <?php foreach ($dataProvider->models as $submission): ?>
        <div class="panel">
            <div class="panel-body">
                <h4><?= Yii::t('app', 'โครงการ') ?>: <?= $submission->project->project_code ?></h4>
                <div><?= $submission->project->name_thai ?></div>
                <h4><?= Yii::t('app', 'หัวหน้าโครงการ/สังกัด') ?></h4>
                <div>
                    <?= $submission->projectLeader->person->fullName ?>
                    <?= Yii::t('app', 'คณะ') . (isset($submission->projectLeader->person->department_id) ? $submission->projectLeader->person->department->name : Yii::t('app', "ไม่กำหนด")) ?>
                    <?= Yii::t('app', 'เบอร์โทรศัพท์') . ' ' . $submission->projectLeader->person->mobile_no ?>
                </div>
                <?php
                if ($submission->getProjectResearchers()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->acknowledgeStatus(100)->exists()) {
                    echo \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ตอบรับร่วมโครงการ'), ['project-researcher/accept', 'id' => $submission->id, 'personId' => \Yii::$app->user->identity->person->id], [
                        'class' => 'btn btn-block btn-primary',
                        'role' => 'modal-remote', 'title' => 'ตอบรับร่วมโครงการ',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                        'data-confirm-message' => Yii::t('app', 'ต้องการตอบรับร่วมโครงการใช่หรือไม่ ?'),
                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                        'data-confirm-cancel' => Yii::t('app', 'ไม่')
                    ]);
                }
                if ($submission->getProjectConsultants()->isDeleted(FALSE)->person(Yii::$app->user->identity->person->id)->acknowledgeStatus(100)->exists()) {
                    echo \yii\helpers\Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ตอบรับเป็นที่ปรึกษาโครงการ'), ['project-consultant/accept', 'id' => $submission->id, 'personId' => \Yii::$app->user->identity->person->id], [
                        'class' => 'btn btn-block btn-primary',
                        'role' => 'modal-remote', 'title' => 'ตอบรับเป็นที่ปรึกษาโครงการ',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => Yii::t('app', 'ยืนยันการตอบรับร่วมโครงการ'),
                        'data-confirm-message' => Yii::t('app', 'ต้องการตอบรับร่วมโครงการใช่หรือไม่ ?'),
                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                        'data-confirm-cancel' => Yii::t('app', 'ไม่')
                    ]);
                }
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php Pjax::end(); ?>

