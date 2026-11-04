<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\QuestionnaireChoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="questionnaire-choice-index">
    <div class="ajaxCrudDatatable panel panel-default">
<?=
GridView::widget([
    'id' => 'crud-datatable-questionnaire-choice',
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'floatHeader' => true,
    'floatHeaderOptions' => ['top' => 66],
    'pjax' => true,
    'columns' => require(__DIR__ . '/_columns.php'),
    'toolbar' => [
            [
            'options' => [
                'class' => '',
            ],
            'content' =>
            Html::a('<i class="glyphicon glyphicon-plus"></i> เพิ่มตัวเลือกของแบบประเมิน', ['questionnaire-choice/create', 'questionTitleID' => $questionTitle->id], ['role' => 'modal-remote', 'title' => Yii::t('app', 'เพื่มตัวเลือกแบบสอบถาม'), 'class' => 'btn btn-success']) .
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

