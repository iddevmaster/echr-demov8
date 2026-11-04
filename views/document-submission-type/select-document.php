<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
$Role = \app\models\Role::findOne($roleId);

$this->title = Yii::t('app', 'กำหนดเอกสารประกอบงานวิจัยกับประเภทโครงการวิจัย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'จัดการค่าตั้งต้น'), 'url' => ['site/master-list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'เลือกประเภทโครงการวิจัย'), 'url' => ['document-submission-type/index', 'roleId' => $roleId]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-transaction">
    <div class="panel">
        <div class="panel-heading"><h3 class="panel-title"><?= $submissionType->name .  Yii::t('app', 'เอกสารสำหรับ : ') . $Role->name ?></h3></div>

        <div class="panel-body">
            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/document/list-document-select.php', [
                    'searchModel' => $DsearchModel,
                    'dataProvider' => $DdataProvider,
                    'selectDocument' => $selectDocument,
                    'submissionTypeId' => $submissionType->id,
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/document-submission-type/list-document-submission-type-select.php', [
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
    $(document).on('pjax:complete', '#crud-datatable-document-submission-type-pjax', function(){
        $.pjax.reload({container: '#crud-datatable-document-pjax'});
    });
js;
$this->registerJs($js);
