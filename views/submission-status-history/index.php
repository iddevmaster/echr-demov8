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
?>
<div class="submission-status-history-index">
    <div id="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
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
                    'content' =>
                    Html::a('<i class="icon wb-print"></i> ' . Yii::t('app', 'ประวัติการดำเนินงาน'), ['submission-status-history/index-researcher', 'pdf' => 1, 'submissionId' => $submissionId,'id'=>$id], ['class' => 'btn btn-success btn-raised','data-pjax'=>0,'target'=>'_blank']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
//            'summary' => false,
//            'toolbar' => true,
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
