<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AlertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'การเตือน';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="alert-index">
    <div class="ajaxCrudDatatable panel panel-default">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'toolbar' => [
                ['content' => ''
//                    Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], ['role' => 'modal-remote', 'title' => Yii::t('app', 'เพื่มคำนำหน้าชื่อ'), 'class' => 'btn btn-success']) .
//                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''], ['data-pjax' => 1, 'class' => 'btn btn-default grey-600', 'title' => 'โหลดใหม่'])
//                    '{toggleData}' .
//                    '{export}'
                ],
            ],
            'toggleDataOptions' => [
                'all' => [
                    'icon' => 'resize-full',
                    'class' => 'btn btn-default grey-600',
                ],
                'page' => [
                    'icon' => 'resize-small',
                    'class' => 'btn btn-default grey-600',
                ],
            ],
            'panelBeforeTemplate' => '
                        <div class="pull-left">{summary}</div>
                        <div class="pull-right">
                            <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
                                {toolbar}
                            </div>    
                        </div>
                        {before}
                        <div class="clearfix"></div>',
            'panel' => [
                'type' => 'primary',
                'heading' => false,
                'before' => false,
                'beforeOptions' => [
                    'class' => 'kv-panel-before bg-primary',
                ],
            ],
            'pager' => array(
                'firstPageLabel' => '<i class="icon md-skip-previous"></i>',
                'lastPageLabel' => '<i class="icon md-skip-next"></i>',
                'prevPageLabel' => '<i class="icon md-fast-rewind"></i>',
                'nextPageLabel' => '<i class="icon md-fast-forward"></i>',
            ),
            'exportConfig' => [
                GridView::EXCEL => true,
            ],
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
        ])
        ?>
    </div>
</div>