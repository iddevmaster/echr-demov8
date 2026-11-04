<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectResearcherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('app', 'Project Researchers');
//$this->params['breadcrumbs'][] = $this->title;
//CrudAsset::register($this);
?>
<div class="project-researcher-index">
    <div class="ajaxCrudDatatable">
        <p class="red-700 font-size-20"><i class="icon md-alert-octagon" aria-hidden="true"></i> <?= Yii::t('app', 'หากผู้ร่วมวิจัยมีหน้าที่เป็นอาจารย์ที่ปรึกษาร่วมด้วยโปรดระบุข้อมูลผู้วิจัยร่วมท่านนั้นในขั้นตอน "อาจารย์ที่ปรึกษา" ด้วย') ?></p>
        <?=
        GridView::widget([
            'id' => 'crud-datatable-project-researcher',
            'dataProvider' => $dataProvider,
//            'filterModel' => $searchModel,
//            'floatHeader' => true,
//            'floatHeaderOptions' => ['top' => 66],
            'pjax' => true,
            'pjaxSettings' => [
//                'beforeGrid' => '<input type="hidden" name="step" value="2"',
            ],
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'options' => [
                        'class' => '',
                    ],
                    'content' =>
                    (isset($submission->submissionType) && !isset($submission->ref_submission_id) && $submission->submissionType->submission_type_group_id == \app\models\SubmissionTypeGroup::GROUP_CONT ? "" : Html::a('<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('app', 'เพิ่มผู้ร่วมวิจัยในโครงการ'), ['project-researcher/create', 'submissionId' => $submission->id], ['role' => 'modal-remote', 'class' => 'btn btn-success btn-raised'])) .
                    Html::a('<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('app', 'โหลดใหม่'), ['submission/new', 'submissionId' => $submission->id, 'step' => $step], ['data-pjax' => 1, 'class' => 'btn btn-default btn-raised'])
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
    <div class="form-group">
        <div class="pull-left">
            <?= Html::submitButton(Yii::t('app', 'ก่อนหน้า'), ['class' => 'btn btn-primary btn-prev', 'name' => 'previousStep', 'value' => $step - 1, 'data-pjax' => 0]) ?>
        </div>
        <div class="pull-right">
            <?= Html::submitButton(Yii::t('app', 'ถัดไป'), ['class' => 'btn btn-primary btn-next', 'name' => 'nextStep', 'value' => $step + 1, 'data-pjax' => 0]) ?>
        </div>
    </div>
</div>
<?php
// Portal the qualification-warning tooltips to <body> so they escape the grid's
// overflow:auto wrappers (.kv-grid-wrapper / .table-responsive) and aren't clipped
// behind the table header. Re-run after each PJAX reload so tooltips on freshly
// rendered rows keep working. Scoped to this grid only (theme site.js untouched).
$gridId = 'crud-datatable-project-researcher';
$this->registerJs(<<<JS
(function () {
    function initQualTips(\$scope) {
        \$scope.find('[data-toggle="tooltip"]').tooltip('destroy').tooltip({ container: 'body' });
    }
    var \$pjax = $('#{$gridId}-pjax');
    var \$grid = \$pjax.length ? \$pjax : $('#{$gridId}').closest('.ajaxCrudDatatable');
    initQualTips(\$grid);                                  // initial render
    \$pjax.off('pjax:complete.qualTip').on('pjax:complete.qualTip', function () {
        initQualTips($(this));                            // after every PJAX reload
    });
})();
JS
);
