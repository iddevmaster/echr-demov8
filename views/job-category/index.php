<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JobCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'ความชำนาญในที่ประชุม');
$this->params['breadcrumbs'][] = ['label' => 'จัดการค่าตั้งต้น', 'url' => ['site/master-list']];

$this->params['breadcrumbs'][] = $this->title;

//CrudAsset::register($this);

?>
<div class="job-category-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-job-category',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
            'pjaxSettings' => [
                'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel]),
            ],
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                    [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'ความชำนาญในที่ประชุม'), ['job-category/create'], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
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

