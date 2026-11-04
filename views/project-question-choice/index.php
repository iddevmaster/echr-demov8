<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectQuestionChoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$dataProvider->pagination = false;
?>
<div class="project-question-choice-index">
    <div class="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-project-question-choice-'.$searchModel->project_question_id,
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax'=>true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['model'=>$searchModel]),
//            ],
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' .Yii::t('app', 'เพิ่มคำตอบที่เลือกได้'), ['project-question-choice/create', 'questionId' => $searchModel->project_question_id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) 
                ],
            ],    
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
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
