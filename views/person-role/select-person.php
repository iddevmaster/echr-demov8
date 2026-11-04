<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
$this->title = Yii::t('app', 'กำหนดสิทธิผู้ใช้งาน');
$this->params['breadcrumbs'][] = ['label' => 'จัดการค่าตั้งต้น', 'url' => ['site/master-list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-transaction">
    <div class="panel">
        <div class="panel-heading"><h3 class="panel-title"><?= $roleId->name ?></h3></div>

        <div class="panel-body">
            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/person/list-person-select.php', [
                    'searchModel' => $DsearchModel,
                    'dataProvider' => $DdataProvider,
                    'selectPerson' => $selectPerson,
                    'roleId'=>$roleId->id,
                ]);
                ?>
            </div>

            <div class="col-md-6">
                <?php
                echo $this->renderFile('@app/views/person-role/list-person-role-select.php', [
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
    $(document).on('pjax:complete', '#crud-datatable-person-role-pjax', function(){
        $.pjax.reload({container: '#crud-datatable-person-pjax'});
    });
js;
$this->registerJs($js);
