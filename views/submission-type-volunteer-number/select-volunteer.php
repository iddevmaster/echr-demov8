<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
$this->title = Yii::t('app', 'กำหนดอาสาสมัครของประเภทโครงการ');
$this->params['breadcrumbs'][] = ['label' => 'จัดการค่าตั้งต้น', 'url' => ['site/master-list']];
$this->params['breadcrumbs'][] = ['label' => 'เลือกประเภทโครงการวิจัย', 'url' => ['submission-type-volunteer-number/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-transaction">
    <div class="panel">
        <div class="panel-heading"><h3 class="panel-title"><?= $submissionType->name ?></h3></div>

        <div class="panel-body">
            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/volunteer-number/list-volunteer-select.php', [
                    'searchModel' => $DsearchModel,
                    'dataProvider' => $DdataProvider,
                    'selectDocument' => $selectDocument,
                    'submissionTypeId'=>$submissionType->id,
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/submission-type-volunteer-number/list-volunteer-submission-type-select.php', [
                    'searchModel' => $SsearchModel,
                    'dataProvider' => $SdataProvider,
                ]);
                ?>
            </div>
        </div>
        <div class="panel-footer"></div>
    </div>
</div>
<?php
$js = <<<js
    $(document).on('pjax:complete', '#crud-datatable-submission-type-volunteer-number-pjax', function(){
        $.pjax.reload({container: '#crud-datatable-volunteer-number-pjax'});
    });
js;
$this->registerJs($js);
