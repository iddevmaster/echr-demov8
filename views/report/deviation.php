<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\export\ExportMenu;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'รายงาน Deviation');
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="deviation-index">
    <?php if ($pdf <> 1): ?>
        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10">
                <?= $this->render('_search-deviation', ['searchModel' => $searchModel]) ?>
            </div>
        </div>

        <?= Html::a(Yii::t('app', "EXPORT PDF"), ['report/deviation', 'isLeader' => $searchModel->is_leader, 'isOngoing' => $searchModel->isOngoing, 'name' => $searchModel->name, 'searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'pdf' => true], ['class' => 'btn btn-default pull-right btn-lg margin-10', 'type' => "submit", 'target' => '_blank']) ?>
    <?php endif; ?>
    <h3 class="panel-title text-center"><?= $this->title ?></h3><br>

    <?php
    foreach ($dataProvider->models as $m) :
        $dcount = app\models\DeviationEvent::find()->joinWith(['submission'])->isDeleted(false)->project($m->project_id)->statusSubmission(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();
        $ds = app\models\DeviationEvent::find()->joinWith(['submission'])->isDeleted(false)->project($m->project_id)->statusSubmission(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->all();

        $dMacount = app\models\DeviationEvent::find()->joinWith('submission')->isDeleted(false)->isMajorMinorCom(app\models\DeviationEvent::MAJOR)->project($m->project_id)->statusSubmission(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();
        $dMicount = app\models\DeviationEvent::find()->joinWith('submission')->isDeleted(false)->isMajorMinorCom(app\models\DeviationEvent::MINOR)->project($m->project_id)->statusSubmission(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();
        $dNccount = app\models\DeviationEvent::find()->joinWith('submission')->isDeleted(false)->isMajorMinorCom(app\models\DeviationEvent::NON)->project($m->project_id)->statusSubmission(\app\models\Submission::STATUS_STAFF_UPLOAD_RESULTDOCUMENT)->count();

        $deviationMaTypes = \app\models\DeviationEventType::find()->joinWith(['submissionEvent.submission'])->project($m->project_id, app\models\DeviationEvent::MAJOR)->isDeleted(false)->groupBy('deviation_event_type.deviation_type_id')->all();
        $deviationMiTypes = \app\models\DeviationEventType::find()->joinWith(['submissionEvent.submission'])->project($m->project_id, app\models\DeviationEvent::MINOR)->isDeleted(false)->groupBy('deviation_event_type.deviation_type_id')->all();
        $deviationNcTypes = \app\models\DeviationEventType::find()->joinWith(['submissionEvent.submission'])->project($m->project_id, app\models\DeviationEvent::NON)->isDeleted(false)->groupBy('deviation_event_type.deviation_type_id')->all();
        ?>

        <div class="panel panel-bordered margin-bottom-10">
            <div class="padding-10" style="padding: 10px;">
                <h3 class="card-title"><font style="font-weight: bold;" color="#073453;"><?= Yii::t('app', 'หมายเลขโครงการ : ') . $m->project->project_code ?> <?= Yii::t('app', 'หัวหน้าโครงการ :  ') . isset($m->projectLeader->person_id) ? $m->projectLeader->person->fullName : "" ?></font></h3>
                <div class="card-block">
                    <h4 class="card-title"> รายงานเบี่ยงเบนทั้งสิ้นจำนวน <?= $dcount; ?>  เหตุการณ์ ได้แก่
                        <?php
                        foreach ($ds as $d):
                            echo '<font style="font-weight: bold;" color="#073453;">หมายเลข : </font>' . $d->submissionEvent->event_no . ' ';
                        endforeach;
                        ?>
                    </h4>
                    <ul>
                        <li>Deviation - Major protocol violation   <?= $dMacount ?>  เหตุการณ์</li>
                        <?php
                        foreach ($deviationMaTypes as $deviationTypeMa) :
                            $tMacount = \app\models\DeviationEventType::find()->joinWith(['submissionEvent.submission'])->isDeleted(false)->project($m->project_id, app\models\DeviationEvent::MAJOR)->deviationType($deviationTypeMa->deviation_type_id)->count();
                            ?>
                            <p style="padding-left: 30px;">- <?= $deviationTypeMa->deviationType->name; ?> จำนวน <?= $tMacount ?> เหตุการณ์</p>
                        <?php endforeach; ?>
                        <li>Deviation - Minor protocol violation <?= $dMicount ?>  เหตุการณ์</li>
                        <?php
                        foreach ($deviationMiTypes as $deviationTypeMi) :
                            $tMicount = \app\models\DeviationEventType::find()->joinWith(['submissionEvent.submission'])->isDeleted(false)->project($m->project_id, app\models\DeviationEvent::MINOR)->deviationType($deviationTypeMi->deviation_type_id)->count();
                            ?>
                            <p style="padding-left: 30px;">- <?= $deviationTypeMi->deviationType->name; ?> จำนวน <?= $tMicount ?> เหตุการณ์</p>
                        <?php endforeach; ?>
                        <li>Deviation - Non compliance <?= $dNccount ?> เหตุการณ์</li>
                        <?php
                        foreach ($deviationNcTypes as $deviationTypeNc) :
                            $tNccount = \app\models\DeviationEventType::find()->joinWith(['submissionEvent.submission'])->isDeleted(false)->project($m->project_id, app\models\DeviationEvent::NON)->deviationType($deviationTypeNc->deviation_type_id)->count();
                            ?>
                            <p style="padding-left: 30px;">- <?= $deviationTypeNc->deviationType->name; ?> จำนวน <?= $tNccount ?> เหตุการณ์</p>
                        <?php endforeach; ?>

                    </ul>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>