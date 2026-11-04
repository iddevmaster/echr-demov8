<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SubmissionDocumentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Submission Documents');
//$this->params['breadcrumbs'][] = $this->title;
//
//CrudAsset::register($this);
//echo $form->errorSummary([$submissionDoc]);
?>
<div class="person-training-index">
    <div class="ajaxCrudDatatable">
<div class="alert dark alert-warning alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                  ต้องลงนามและระบุวันที่ในเอกสาร หากไม่ระบุจะถือว่าเอกสารไม่สมบูรณ์!!
                </div>
        <?=
        GridView::widget([
            'id' => 'crud-datatable-person-training',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
//            'pjaxSettings' => [
//                'beforeGrid' => $this->render('_search', ['searchModel'=>$searchModel]),
//            ],
            'columns' => require(__DIR__ . '/_columns-register.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> ' .Yii::t('app', 'เพิ่มข้อมูลการอบรม'), ['person-training/create','personId'=>$person->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised'])
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
        <?= Html::submitButton(Yii::t('app', '<span><i class="icon wb-arrow-left" aria-hidden="true"></i> ก่อนหน้า </span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary btn-prev', 'name' => 'previousStep', 'value' => $step-1]) ?>
    </div>
    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', '<span>ถัดไป <i class="icon wb-arrow-right" aria-hidden="true"></i></span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary btn-next', 'name' => 'nextStep', 'value' => $step+1]) ?>
    </div>
</div>
