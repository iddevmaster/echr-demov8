<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\TopservReceiptH;
use app\models\TopservReceiptTax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TopservReceiptHSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dataProvider->pagination = false;

?>
<div class="topserv-receipt-tax-index">
    <div class="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-topserv-select-rep',
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax'=>true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['model'=>$searchModel]),
//            ],
            'columns' => require(__DIR__.'/_columns-search-amendment-docs.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' => ''
//                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' .Yii::t('app', 'เพิ่มรายการรับชำระหน้าร้าน'), ['create', 'cashDeskId' => $searchModel->cash_desk_id, 'type' => TopservReceiptH::TYPE_WALKIN], ['data-pjax' => '0', 'class' => 'btn btn-success btn-raised']) .
//                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' .Yii::t('app', 'เพิ่มรายการรับชำระหนี้'), ['create', 'cashDeskId' => $searchModel->cash_desk_id, 'type' => TopservReceiptH::TYPE_CREDIT], ['data-pjax' => '0', 'class' => 'btn btn-warning btn-raised']) .
//                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' .Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],    
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
//            'showPageSummary' => true,
//            'pageSummaryRowOptions' => ['class' => 'kv-page-summary info'],
            'panel' => [
                'type' => 'primary',
                'heading' => FALSE,
                'before' => '<div class="pull-left">{summary}</div>',
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
<?php

//$css = <<<css
//    .modal-dialog.modal-lg {
//        min-width: 1200px !important;
//    }
//css;
//$this->registerCss($css);