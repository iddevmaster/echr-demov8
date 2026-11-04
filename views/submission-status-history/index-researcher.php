<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionStatusHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//
//$this->title = Yii::t('app', 'Submission Status Histories');
//$this->params['breadcrumbs'][] = $this->title;
//
//CrudAsset::register($this);
//$submission = app\models\Submission::findOne($submissionId);
?>
<div class="submission-status-history-index">
    <table id="w4" class="table table-striped table-bordered detail-view">
        <tbody><tr><th style=" text-align: left; padding-left: 10px;">ประเภทการขอรับพิจารณา</th><td style=" padding-left: 10px;"><?= $submission->submissionType->name; ?></td></tr>
            <tr><th  style=" text-align: left; padding-left: 10px;">หมายเลขโครงการ Project Code</th><td style=" padding-left: 10px; "><span class="" title="" data-toggle="tooltip" data-placement="bottom" data-original-title=""><?= isset($submission->project->project_code) ? $submission->project->project_code : "N/A"; ?></span></td></tr>
            <tr><th  style=" text-align: left; padding-left: 10px;">ชื่อโครงการภาษาไทย Research Title (Thai)</th><td style=" padding-left: 10px;"><span class="" title="" data-toggle="tooltip" data-placement="bottom" data-original-title=""><?= isset($submission->project->name_thai) ? $submission->project->name_thai : "N/A"; ?></span></td></tr>
            <tr><th  style=" text-align: left; padding-left: 10px;">ชื่อโครงการภาษาอังกฤษ Research Title (English)</th><td style=" padding-left: 10px;"><span class="" title="" data-toggle="tooltip" data-placement="bottom" data-original-title=""><?= isset($submission->project->name_eng) ? $submission->project->name_eng : "N/A"; ?></span></td></tr>
            <tr><th  style=" text-align: left; padding-left: 10px;">หัวหน้าโครงการ </th><td style=" padding-left: 10px;"><?= isset($submission->projectLeader->person_id) ? $submission->projectLeader->person->i18nFullName : "N/A"; ?></td></tr>
            </tbody></table>

    <div id="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
            'pjaxSettings' => [
//                'beforeGrid' => '<input type="hidden" name="step" value="2"',
            ],
            'columns' => require(__DIR__ . '/_columns-researcher.php'),
            'striped' => false,
            'condensed' => false,
            'responsive' => false,
            'summary' => false,
            'toolbar' => FALSE,
            'panel' => [
                // 'type' => 'primary',
                'heading' => Yii::t('app', 'ประวัติการดำเนินงาน'),
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
