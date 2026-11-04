<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RegisterGroupPersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'กำหนดผู้เข้าร่วมประชุมตามรอบ');
//CrudAsset::register($this);
?>
<div class="person-role-index">
    <div class="ajaxCrudDatatable panel panel-default">
        <?=
        GridView::widget([
            'id' => 'crud-datatable-person-role',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjaxSettings' => [
                'options' => [
                    'clientOptions' => ['skipOuterContainers' => true]
                ],
                'beforeGrid' => $this->render('_search', ['searchModel' => $searchModel, 'submissionId' => $submissionId]),
            ],
            'toolbar' => [
                ['content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default grey-600', 'title' => yii::t('app', 'โหลดใหม่')])
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
//            'panelBeforeTemplate' => '
//                        <div class="pull-left">{summary}</div>
//                        <div class="pull-right">
//                            <div class="btn-toolbar kv-grid-toolbar" role="toolbar">
//                                {toolbar}
//                            </div>    
//                        </div>
//                        {before}
//                        <div class="clearfix"></div>',
            'panel' => [
                'after' => FALSE,
                'heading' => '<i class="glyphicon glyphicon-list"></i> รายชื่อกรรมการทั้งหมดที่สามารถเลือกเพื่อให้อ่านงานวิจัยในโครงการนี้',
//                'beforeOptions' => [
//                    'class' => 'kv-panel-before bg-primary',
//                ],
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
            'columns' => require(__DIR__ . '/_columns-list-person-committee-select.php'),
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
        ])
        ?>
    </div>
</div>
<?php
$elOrg = Html::getInputId($searchModel, 'personOrg');
$elDep = Html::getInputId($searchModel, 'personDepartment');
$elDivision = Html::getInputId($searchModel, 'personDivision');

$js = <<<js
    $(document).on('pjax:complete', '#crud-datatable-person-role-pjax', function() {
        var elOrg = $("#{$elOrg}"), // your input id for the HTML select input
            settings = elOrg.attr('data-krajee-select2');
        settings = window[settings];
        // reinitialize plugin
        elOrg.select2(settings);
        $('.loading-{$elOrg}').hide();
        
        
        var elOrg = $("#{$elDep}"), // your input id for the HTML select input
            settings = elOrg.attr('data-krajee-select2');
        settings = window[settings];
        // reinitialize plugin
        elOrg.select2(settings);
        $('.loading-{$elDep}').hide();

           var elOrg = $("#{$elDivision}"), // your input id for the HTML select input
            settings = elOrg.attr('data-krajee-select2');
        settings = window[settings];
        // reinitialize plugin
        elOrg.select2(settings);
        $('.loading-{$elDivision}').hide();
    });

js;
$this->registerJs($js);
