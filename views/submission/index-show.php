<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Submission;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'รายการส่งโครงการวิจัย');
//$this->params['breadcrumbs'][] = $this->title;

if (isset($status)) {
    $st = Submission::getStatusLabels()[$status];
} elseif (isset($resolution)) {
    $st = Submission::getResolutionLables()[$resolution];
}
$currentRole = Yii::$app->session->get('currentRole');

?>
<div class="submission-index ">
   
    <div class="ajaxCrudDatatable" >
        <?php if ($currentRole['role_id'] != app\models\Role::COPRESIDENT and $currentRole['role_id'] != app\models\Role::PRESIDENT and $currentRole['role_id'] != app\models\Role::SECRETARY  and $currentRole['role_id'] != app\models\Role::STAFF and $currentRole['role_id'] != app\models\Role::COMMITTEE and $currentRole['role_id'] != app\models\Role::ADMIN ) { ?>
            <?= $this->render('_search-show', ['searchModel' => $searchModel]) ?>
        <?php } ?>
        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission-' . $panelId,
            'dataProvider' => $dataProvider,
            'floatHeader' => true,
            'floatHeaderOptions' => ['top' => 66],
            'pjax' => TRUE,

            'columns' => require(__DIR__ . '/_columns-show.php'),
            'toolbar' => [
                    [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), Url::current(), ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'info',
                'heading' => isset($st) ? yii::t('app', ':: สถานะของการแสดงข้อมูล ') . $st : yii::t('app', 'ข้อมูลงานวิจัย Panel : '). $panelId,
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

