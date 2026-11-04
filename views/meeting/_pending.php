<?php
    use kartik\grid\GridView;
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<div class="meeting-index">
    <div id="ajaxCrudDatatable">
        
        <?=
        GridView::widget([
            'id' => 'crud-datatable-pending-meeting',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => false,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel]),
//            ],
            'columns' => require(__DIR__ . '/_pending-columns.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
//                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มการประชุม'), ['meeting/create'], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
//            'panel' => [
//                'type' => 'primary',
//                'heading' => FALSE,
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
