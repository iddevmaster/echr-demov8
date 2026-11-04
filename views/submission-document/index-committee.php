<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
$currentRoles = Yii::$app->session->get('currentRole');
$toolbar = [
    [
        'options' => [
            'class' => '',
        ],
        'content' =>
        Html::a('<i class="glyphicon glyphicon-download"></i> ' . Yii::t('app', 'ดาวน์โหลด'), ['submission-document/download-merge', 'submissionId' => $submission->id], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised btn-download-merge'])
    ],
];
/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Submission Documents');
//$this->params['breadcrumbs'][] = $this->title;
//
//CrudAsset::register($this);
//echo $form->errorSummary([$submissionDoc]);
$dataProvider->pagination = false;
?>
<div class="submission-document-index">
    <div class="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission-document',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel'=>$searchModel]),
//            ],
            'columns' => require(__DIR__ . '/_columns-committee.php'),
            'toolbar' => $toolbar,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'summary' => false,
            'panel' => [
                // 'type' => 'primary',
                'heading' => Yii::t('app', 'รายการเอกสารโครงการวิจัยเพื่อพิจารณา'),
                'before' => '<div class="pull-left">{summary}</div>',
                'after' => FALSE,
                'footer' => FALSE,
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
    <?php
//    if ($currentRoles['role_id'] == \app\models\Role::COMMITTEE || $currentRoles['role_id'] == \app\models\Role::STAFF || $currentRoles['role_id'] == \app\models\Role::ADMIN ) {
//        echo $upload;
//    }
    ?>