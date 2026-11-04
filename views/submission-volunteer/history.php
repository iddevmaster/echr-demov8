<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionVolunteerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="submission-volunteer-index">
    <div class="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-submission-volunteer-history',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax'=>true,
            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['model'=>$searchModel]),
            ],
            'columns' => require(__DIR__.'/_columns-history.php'),
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
