<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionCommitteeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Submission Committees');
//$this->params['breadcrumbs'][] = $this->title;
//CrudAsset::register($this);
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
$this->title = Yii::t('app', 'รายงานสถิติการอ่านและเข้าประชุมของกรรมการ');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="submission-committee-index">
    <div class="panel panel-bordered">
        <div class="panel-body">
            <div class="panel-heading "><div class="panel-title"><?= $this->render('_search', ['searchModel' => $searchModel]) ?></div>
        </div>  

            <div class="row text-center"><h4><?= Yii::t('app', 'หลักฐานการจ่ายค่าตอบแทนการอ่านพิจารณาโครงการวิจัย'); ?></h4></div>
            <div class="row text-center"><h4><?= Yii::t('app', 'ส่วนราชการ สำนักงานคณะกรรมการจริยธรรมการวิจัยในมนุษย์'); ?></h4></div>
            <div class="row text-center"><h4><?= Yii::t('app', 'การพิจารณาโครงการวิจัยในมนุษย์ ประจำสาขาวิชาที่'); ?> <?php
                    if (isset($submission)) {
                        echo $submission->project->panel_id;
                    }
                    ?> <?php
                    if (isset($submission)) {
                        echo $submission->submissionType->name;
                    }
                    ?> <?= Yii::t('app', 'เลขที่โครงการ'); ?>  <?php
                    if (isset($submission)) {
                        echo $submission->project->project_code;
                    }
                    ?></h4></div>

            <div class="text-right"><?= $fullExportMenu; ?></div>
            <div id="ajaxCrudDatatable">
                <?=
                GridView::widget([
                    'id' => 'crud-datatable-submission-committees',
                    'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
                    'pjax' => true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel]),
//            ],
                    'columns' => require(__DIR__ . '/_columns-report.php'),
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'summary' => false,
                    'toolbar' => FALSE,
//            'panel' => [
//                // 'type' => 'primary',
//              //  'heading' => Yii::t('app', 'กรรมการที่ประเมินงานวิจัย'),
//                'before' => '<div class="pull-left">{summary}</div>',
////                'after' => FALSE,
////                'footer' => FALSE,
//            ],
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
    </div>
</div>

