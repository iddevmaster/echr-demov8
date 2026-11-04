<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionCommitteeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Submission Committees');
//$this->params['breadcrumbs'][] = $this->title;

//CrudAsset::register($this);

?>
<div class="submission-committee-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable-submission-committees',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'summary' => false,
            'toolbar' => FALSE,
            'panel' => [
                // 'type' => 'primary',
                'heading' => Yii::t('app', 'กรรมการที่ประเมินงานวิจัย'),
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

