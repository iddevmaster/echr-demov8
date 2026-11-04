<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Submission;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'งานวิจัยที่กำหนดให้เป็น Monitor');
$this->params['breadcrumbs'][] = $this->title;

//CrudAsset::register($this);
?><Br>
<div class="submission-index ">
    <div id="ajaxCrudDatatable" >
        <?=  $this->render('_search-monitor', ['searchModel' => $searchModel]) ?>
        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => FALSE,
            'pjaxSettings' => [
                
                'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel]),
            ],
            'columns' => require(__DIR__ . '/_columns-ismonitor.php'),
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
            'panel' => [
                'type' => 'info',
//                'heading' => isset($st) ? yii::t('app', ':: สถานะของการแสดงข้อมูล ') . $st : yii::t('app', 'งานวิจัยที่มีชื่อเป็นผู้ร่วมวิจัย '),
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

