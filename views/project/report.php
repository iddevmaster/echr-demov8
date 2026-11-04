<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
if($date == 'expire_at'){
    $title = 'รายงานโครงการที่กำลังจะหมดอายุรับรอง';
}else{
    $title = 'รายงานโครงการที่จะต้องรายงานความก้าวหน้า';    
}
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายงาน'), 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="project-index">
    <div id="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable-project',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
            'pjaxSettings' => [
                'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel,'date'=>$date]),
            ],
            'columns' => require(__DIR__ . '/_columns-report.php'),

            'striped' => true,
            'condensed' => true,
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

