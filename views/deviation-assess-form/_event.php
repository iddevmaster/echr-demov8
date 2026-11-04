<?php
use kartik\grid\GridView;
?>
<div class="submission-event-index">
    <div class="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-submission-event',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax'=>true,
            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['model'=>$searchModel]),
            ],
            'columns' => require(__DIR__.'/_columns-event.php'),
            'toolbar' => false,    
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary',
                'heading' => FALSE,
                'before' => false,
                'after' => FALSE,
                'footer' => FALSE,
            ],
            'pager' => array(
                'firstPageLabel' => '<i class="icon md-skip-previous"></i>',
                'lastPageLabel' => '<i class="icon md-skip-next"></i>',
                'prevPageLabel' => '<i class="icon md-fast-rewind"></i>',
                'nextPageLabel' => '<i class="icon md-fast-forward"></i>',
            ),
        ])?>
    </div>
</div>