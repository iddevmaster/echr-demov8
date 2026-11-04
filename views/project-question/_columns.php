<?php

use yii\helpers\Url;
use app\models\ProjectQuestionChoiceSearch;
use kartik\grid\GridView;
use yii\helpers\Html;

return [
    //[
    //    'class' => 'kartik\grid\CheckboxColumn',
    //    'width' => '20px',
    //],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'id',
    // ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'name',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'answerTypeLabel',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updatedBy.person.fullName',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'updated_at',
        'format' => ['date', 'php:d/m/Y H:i:s'],
        'filter' => FALSE,
    ],
    [
        'class' => 'kartik\grid\ExpandRowColumn',
//        'width' => '50px',
        'value' => function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function ($model, $key, $index, $column) {
            $searchModel = new ProjectQuestionChoiceSearch();
            $searchModel->deleted = 0;
            $searchModel->project_question_id = $model->id;
            $dataProvider = $searchModel->search([]);
            return Yii::$app->controller->renderPartial('@app/views/project-question-choice/index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        },
        'headerOptions' => ['class' => 'kartik-sheet-style'],
        'expandOneOnly' => true,
        'detailRowCssClass' => 'grid-expanded-row-details',
        'hiddenFromExport' => true,
//        'hidden' => true,
//        'enableRowClick' => true,
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'template' => '{select-agenda} {update} {delete}',
        'viewOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'รายละเอียด'), 'data-toggle' => 'tooltip'],
        'updateOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'แก้ไข'), 'data-toggle' => 'tooltip'],
        'deleteOptions' => ['role' => 'modal-remote', 'title' => Yii::t('app', 'ลบ'),
            'data-confirm' => false, 'data-method' => false, // for overide yii data api
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
            'data-confirm-message' => Yii::t('app', 'ต้องการลบรายการนี้ใช่หรือไม่ ?'),
            'data-confirm-ok' => Yii::t('app', 'ใช่'),
            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
        ],
        'buttons' => [
            'select-agenda' => function($url, $model) {
//                \yii\helpers\VarDumper::dump($model->meeting->allInspectorIds);
//                \yii\helpers\VarDumper::dump(in_array($model->person->user_id, $model->meeting->allInspectorIds));
//                echo '<br>';
                $title = Yii::t('app', 'เลือกวาระ');
                return Html::a('<i class="icon md-assignment font-size-18"></i>',
                                ['project-question/select-agenda', 'id' => $model->id],
                                ['role' => 'modal-remote', 'title' => $title,
                ]);
            },
        ],
    ],
];
