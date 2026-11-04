<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Submission;
use app\models\SubmissionCommittee;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'รายงานโครงการวิจัย');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];

$this->params['breadcrumbs'][] = $this->title;

//CrudAsset::register($this);

$currentRole = Yii::$app->session->get('currentRole');
$fullExportMenu = ExportMenu::widget([
            'dataProvider' => $dataProvider,
            'columns' => require(__DIR__ . '/_columns-report.php'),
            'target' => ExportMenu::TARGET_BLANK,
            'fontAwesome' => true,
            'pjaxContainerId' => 'kv-pjax-container',
            'dropdownOptions' => [
                'label' => 'Full',
                'class' => 'btn btn-default',
                'itemsBefore' => [
                    '<li class="dropdown-header">Export All Data</li>',
                ],
            ],
        ]);
?>
<div class="submission-index ">

    <div id="ajaxCrudDatatable" >
        <?= $this->render('_search-report', ['searchModel' => $searchModel,'staff'=> isset($staff)? $staff:"",'status'=>isset($status)? $status : "",'typeGroup'=>isset($typeGroup)? $typeGroup : ""]) ?>

        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => TRUE,
            'pjaxSettings' => [
            //  'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel]),
            ],
            'columns' => require(__DIR__ . '/_columns-report.php'),
            'toolbar' => [
                    [
                    'content' => $fullExportMenu .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'info',
                'heading' => isset($st) ? yii::t('app', ':: สถานะของการแสดงข้อมูล ') . $st : yii::t('app', 'รายการงานวิจัย '),
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

