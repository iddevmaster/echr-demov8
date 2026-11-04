<?php

use yii\helpers\Html;

$currentRoles = Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\RegisterTransaction */
//$this->title = Yii::t('app', 'กำหนดเลขาและกรรมการ');
////$this->params['breadcrumbs'][] = ['label' => 'จัดการค่าตั้งต้น', 'url' => ['site/master-list']];
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-transaction">
    <div class="panel">
        <div class="row">
            <div class="col-md-12 ">
                <div class="alert alert-info alert-dismissible">
                    <?php if (!$id->isFromCrec()) { ?>
                        <?=
                        Yii::t('app', 'วันที่ประมาณการประชุม : ');
                        if (isset($id->meeting_plan_date)) {
                            echo Yii::$app->formatter->format($id->meeting_plan_date, 'date');
                        } else {
                            echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการประชุม');
                        }
                        ?>
                    <?php } ?>
                    <?=
                    Yii::t('app', ' วันที่ประมาณการส่งผลการประเมิน : ');
                    if (isset($id->send_plan_date)) {
                        echo Yii::$app->formatter->format($id->send_plan_date, 'date');
                    } else {
                        echo Yii::t('app', 'ยังไม่กำหนดวันประมาณการส่งเอกสารประเมินของกรรมการ');
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <?php if (in_array($currentRoles['role_id'], [\app\models\Role::STAFF, \app\models\Role::SECRETARY, \app\models\Role::ADMIN])) { ?>
                <div class="col-md-12">
                    <?php
                    echo $this->renderFile('@app/views/submission-committee/list-committee-select.php', [
                        'searchModel' => $comsearchModel,
                        'dataProvider' => $comdataProvider,
                    ]);
                    ?>
                </div>
                <div class="col-md-12">
                    <?php
                    echo $this->renderFile('@app/views/person-role/list-person-committee.php', [
                        'searchModel' => $PsearchModel,
                        'dataProvider' => $PdataProvider,
                        'submissionId' => $id->id,
                        'projectId' => $id->project_id,
                        'submission' => $submission
                    ]);
                    ?>
                </div>
            <?php } ?>

        </div>
        <div class="panel-footer"></div>
    </div>
</div>
<?php
$js = <<<js
    $(document).on('pjax:complete', '#crud-datatable-submission-committee-pjax', function(){
        $.pjax.reload({container: '#crud-datatable-person-role-pjax'});
    });
    $(document).on('pjax:complete', '#crud-datatable-person-role-pjax', function(){
        $.pjax.reload({container: '#submission-status-pjax'});
    });
js;
$this->registerJs($js);
