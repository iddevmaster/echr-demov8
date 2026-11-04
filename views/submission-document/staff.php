<?php

use app\models\SubmissionDocument;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use kartik\sortable\Sortable;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectResearcherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Project Researchers');
//$this->params['breadcrumbs'][] = $this->title;
//CrudAsset::register($this);
$currentRole = \Yii::$app->session->get('currentRole');
// $content = Html::a('<i class="glyphicon glyphicon-download"></i> ' . Yii::t('app', 'ดาวน์โหลด'), ['submission-document/download-merge', 'submissionId' => $submission->id], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised btn-download-merge']);
$content = (($submission->status == app\models\Submission::STATUS_SUBMITTED)) && ($currentRole['role_id'] == \app\models\Role::STAFF) ? Html::a('<i class="glyphicon glyphicon-check"></i> ' . Yii::t('app', 'ตรวจสอบ'), ['submission-document/check-documents', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-warning btn-raised btn-check-documents', 'data-href' => Url::to(['submission-document/check-documents', 'submissionId' => $submission->id])]) : "";
$content .= SubmissionDocument::getDownloadMergeLink($submission);
$content .= (((($submission->submission_type_id == 9 && $currentRole['role_id'] == app\models\Role::STAFF && isset($submission->responsible_person)) || ($submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW && (($submission->status <= \app\models\Submission::STATUS_DOC_APPROVED && $currentRole['role_id'] == app\models\Role::STAFF) || (($submission->status > \app\models\Submission::STATUS_DOC_APPROVED && $currentRole['role_id'] == \app\models\Role::STAFF && $submission->responsible_person == \Yii::$app->user->identity->id))))) || ($currentRole['role_id'] == app\models\Role::ADMIN)) ? Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มเอกสารอื่นๆ'), ['submission-document/create', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) : "" );

Sortable::widget([
    'items' => []
]);
$dataProvider->pagination = false;
?>

<div class="project-submission-document-index">
    <div class="ajaxCrudDatatable">
<?=
GridView::widget([
    'id' => 'crud-datatable-submission-document',
    'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
    'pjax' => true,
    'pjaxSettings' => [
//                'beforeGrid' => '<input type="hidden" name="step" value="2"',
    ],
    'columns' => require(__DIR__ . '/_columns-staff.php'),
    'toolbar' => [
        [
            'options' => [
                'class' => '',
            ],
            'content' => $content,
        ],
    ],
    'tableOptions' => [
        'id' => 'sortable-table-document'
    ],
    'rowOptions' => function ($model) {
        return [
            'data-id' => $model->id
        ];
    },
    'striped' => true,
    'condensed' => true,
    'responsive' => true,
    'summary' => false,
    'panel' => [
        // 'type' => 'primary',
        'heading' => Yii::t('app', 'เอกสารของโครงการวิจัย'),
        'before' => '<div class="pull-left">{summary}</div>',
//                'after' => FALSE,
//                'footer' => FALSE,
    ],
    'pager' => array(
        'firstPageLabel' => '<i class="icon md-skip-previous"></i>',
        'lastPageLabel' => '<i class="icon md-skip-next"></i>',
        'prevPageLabel' => '<i class="icon md-fast-rewind"></i>',
        'nextPageLabel' => '<i class="icon md-fast-forward"></i>',
    ),
]);
?>
    </div>

</div>
