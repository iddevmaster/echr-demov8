<?php

use kartik\grid\GridView;
use yii\helpers\Html;

$currentRole = \Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
if(isset($pjaxId)){
    $loadId = 'crud-datatable-submission-result-document-' . $pjaxId;
}else{
    $loadId = 'crud-datatable-submission-result-document';
}
?>

<div class="submission-document-index">
    <div class="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => $loadId,
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel'=>$searchModel]),
//            ],
            'columns' => require(__DIR__ . '/_letter-result-columns-president.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                   $currentRole['role_id'] == app\models\Role::ADMIN || $currentRole['role_id'] == app\models\Role::STAFF ? Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มเอกสารอื่นๆ'), ['submission-result-document/create', 'submissionId' => $submission->id,'pjaxId'=>$pjaxId], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) : "" .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), \yii\helpers\Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => FALSE,
                'before' => false,
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

