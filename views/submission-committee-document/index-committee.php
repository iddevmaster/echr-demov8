<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use app\models\Role;

$currentRole = \Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Submission Documents');
//$this->params['breadcrumbs'][] = $this->title;
//
//CrudAsset::register($this);
//echo $form->errorSummary([$submissionDoc]);
?>
<div class="submission-document-committee-index">
    <div class="ajaxCrudDatatable">
        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission-committee-document',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel'=>$searchModel]),
//            ],
            'columns' => require(__DIR__ . '/_columns-committee.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                   $currentRole['role_id'] == Role::STAFF || $currentRole['role_id'] == Role::COMMITTEE || $currentRole['role_id'] == Role::ADMIN ?  Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มเอกสารอื่นๆ'), ['submission-committee-document/create', 'submissionId' => $submission->id, 'sCommitteeId' => $sCommitteeId], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) : "" .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), $reloadUrl, ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
                        'summary' => false,

            'panel' => [
//                'type' => 'primary',
                'heading' => 'แบบประเมินสำหรับกรรมการ',
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
<?php
$js = <<<js
    $(document).on('pjax:complete', '#crud-datatable-submission-committee-document-pjax', function(){
        $.pjax.reload({container: '#submission-btn-pjax'});
    });
js;
$this->registerJs($js);