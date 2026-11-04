<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Submission;
use app\models\SubmissionCommittee;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$currentRole = Yii::$app->session->get('currentRole');
?>
<div class="submission-history-index ">
    <div class="ajaxCrudDatatable" >
        
        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission-history',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => TRUE,
            'pjaxSettings' => [
            //  'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel]),
            ],
            'columns' => require(__DIR__ . '/_submission-history-columns.php'),
            'toolbar' => [
                    [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'summary' => false,
            'panel' => [
                'type' => 'default',
                'heading' => \Yii::t('app', 'ประวัติการขอรับพิจารณาโครงการ '),
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

