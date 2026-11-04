<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\SubmissionType;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Submission Documents');
//$this->params['breadcrumbs'][] = $this->title;
//
//CrudAsset::register($this);
//echo $form->errorSummary([$submissionDoc]);
?>
<div class="submission-document-index">
    <div class="ajaxCrudDatatable">
        <?php if($submission->is_legacy == TRUE): ?>
        <div class="alert alert-info dark font-size-18">
            <?= Yii::t('app', 'เอกสารงานวิจัยที่จะนำมาอัฟโหลดจะต้องเป็นเวอร์ชั่นล่าสุดที่ผ่านการรับรองแล้ว') ?>
        </div>
        <?php endif; ?>
        <?=
        GridView::widget([
            'id' => 'crud-datatable-submission-document',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel'=>$searchModel]),
//            ],
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                        ($submission->submission_type_id == SubmissionType::TYPE_AMENDMENT 
                        ? Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'แก้ไขเอกสารเดิมในโครงการ'), ['submission-document/search-amendment-docs', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-info btn-raised'])
                        : "") .
                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มเอกสารใหม่ในโครงการ'), ['submission-document/create', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised']) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), $reloadUrl, ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
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
<div class="form-group">
    <div class="pull-left">
        <?= Html::submitButton(Yii::t('app', 'ก่อนหน้า'), ['class' => 'btn btn-primary btn-prev', 'name' => 'previousStep', 'value' => $step - 1]) ?>
    </div>
    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', 'ถัดไป'), ['class' => 'btn btn-primary btn-next', 'name' => 'nextStep', 'value' => $step + 1]) ?>
    </div>
</div>
