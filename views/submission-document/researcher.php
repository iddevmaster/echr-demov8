<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectResearcherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Project Researchers');
//$this->params['breadcrumbs'][] = $this->title;
//CrudAsset::register($this);
$currentRoles = Yii::$app->session->get('currentRole');

if ($submission->status > app\models\Submission::STATUS_PENDING_SUBMISSION and $submission->status < app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER) {
    $content = Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มเอกสารอื่นๆ'), ['submission-document/create', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) .
            Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised']);
} else {
    $content = Html::a('<i class="glyphicon glyphicon-download"></i> ' . Yii::t('app', 'ดาวน์โหลด'), ['submission-document/download-merge', 'submissionId' => $submission->id], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised btn-download-merge']);
}

//$toolbar = [
//    [
//        'options' => [
//            'class' => '',
//        ],
//        'content' =>
//        Html::a('<i class="glyphicon glyphicon-download"></i> ' . Yii::t('app', 'ดาวน์โหลด'), ['submission-document/download-merge', 'submissionId' => $submission->id], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised btn-download-merge'])
//    ],
//];
//if ($submission->status > app\models\Submission::STATUS_PENDING_SUBMISSION and $submission->status < app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER) {
//    $toolbar = [
//        [
//            'options' => [
//                'class' => '',
//            ],
//            'content' =>
//            Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มเอกสารอื่นๆ'), ['submission-document/create', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) .
//            Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
//        ],
//    ];
//}
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
            'columns' => require(__DIR__ . '/_columns-researcher.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' => $content,
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'summary' => false,
            'panel' => [
                // 'type' => 'primary',
                'heading' => Yii::t('app', 'รายการเอกสาร'),
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
