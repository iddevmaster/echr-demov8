<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Meeting */
$this->title = Yii::t('app', 'รายงานประวัติการเข้าร่วมประชุม');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-view">

    <div class="panel panel-default">
        <div class="panel-body">
            <?php echo $this->render('_search-report', ['searchModel' => $crSearch]) ?>

            <div class="nav-tabs-horizontal">

                <div class="tab-content padding-vertical-15">

                    <div id="ajaxCrudDatatable">
                        <?=
                        GridView::widget([
                            'id' => 'crud-datatable-meeting',
                            'dataProvider' => $crProvider,
//            'filterModel' => $searchModel,
                            'floatHeader' => true,
                            'floatHeaderOptions' => ['top' => 66],
                            'pjax' => FALSE,
                            'pjaxSettings' => [
                                'beforeGrid' => $this->render('_search-report', ['searchModel' => $crSearch]),
                            ],
                            'columns' => require(__DIR__ . '/_columns-report.php'),
//                            'toolbar' => [
//                                    [
//                                    'options' => [
//                                        'class' => '',
//                                    ],
//                                    'content' =>
//                                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มวาระการประชุม'), ['meeting-agenda/create'], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) .
//                                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
//                                ],
//                            ],
                            'striped' => true,
                            'condensed' => true,
                            'showPageSummary' => true,
                            'responsive' => true,
                            'panel' => [
                                'type' => 'primary',
                                'heading' => FALSE,
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
            </div>
        </div>
    </div>
</div>
