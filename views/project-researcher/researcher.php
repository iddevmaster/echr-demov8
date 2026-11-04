<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use kartik\sortable\Sortable;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectResearcherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Project Researchers');
//$this->params['breadcrumbs'][] = $this->title;
//CrudAsset::register($this);
$currentRole = \Yii::$app->session->get('currentRole');
if ($submission->submission_type_id == 9) {
    $staff = 1;
} else {
    $staff = NULL;
}

Sortable::widget([
    'items' => []
]);
$dataProvider->pagination = false;
?>
<div class="project-researcher-index">
    <div class="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable-project-researcher',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
            'pjaxSettings' => [
//                'beforeGrid' => '<input type="hidden" name="step" value="2"',
            ],
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    (((($submission->submission_type_id == 9 && isset($submission->responsible_person)) || $submission->status < app\models\Submission::STATUS_MEETING_DONE || $submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) && $currentRole['role_id'] == app\models\Role::STAFF && !$submission->isFromCrec()) ? Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'นักวิจัยร่วมโครงการ'), ['project-researcher/create', 'submissionId' => $submission->id, 'staff' => $staff], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) : "" ) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default grey-600', 'title' => 'โหลดใหม่'])
                ],
            ],
            'tableOptions' => [
                'id' => 'sortable-table-researcher'
            ],
            'rowOptions' => function ($model) {
                return [
                    'data-id' => $model->id
                ];
            },
            'columns' => require(__DIR__ . '/_columns-researcher.php'),
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'summary' => false,
            'panel' => [
                // 'type' => 'primary',
                'heading' => Yii::t('app', 'รายชื่อผู้ร่วมวิจัย'),
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
