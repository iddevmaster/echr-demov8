<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */

$this->title = Yii::t('app', 'กำหนดหนังสือแจ้งผลที่ใช้สำหรับวาระการประชุม');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'จัดการค่าตั้งต้น'), 'url' => ['site/master-list']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'เลือกวาระการประชุม'), 'url' => ['agenda-result-document/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-transaction">
    <div class="panel">
        <div class="panel-heading"><h3 class="panel-title"><?= $agenda->name?></h3></div>

        <div class="panel-body">
            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/result-document/list-document-select.php', [
                    'searchModel' => $DsearchModel,
                    'dataProvider' => $DdataProvider,
                    'selectDocument' => $selectDocument,
                    'agandaId' => $agenda->id,
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/agenda-result-document/list-document-agenda-select.php', [
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
    $(document).on('pjax:complete', '#crud-datatable-agenda-result-document-pjax', function(){
        $.pjax.reload({container: '#crud-datatable-result-document-pjax'});
    });
js;
$this->registerJs($js);
