<?php

use \app\models\SubmissionCommittee;
use yii\helpers\Html;

$docs = $submission->getSubmissionDocuments()->isDeleted(FALSE)->all();
$committees = $submission->getSubmissionCommittees()->isDeleted(FALSE)->status(SubmissionCommittee::STATUS_RETURN)->all();
?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('app', 'เอกสารโครงการ') ?></h3>
            </div>
            <ul class="list-group list-group-bordered">
                <?php foreach ($docs as $doc) : ?>
                    <li class="list-group-item"><?= $doc->name; ?> <?= $doc->fileLink; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- End Example Panel With List Group -->
    </div>
    <div class="col-md-6">

        <?php
        foreach ($committees as $key => $com) :
            $comDocs = $com->getSubmissionCommitteeDocuments()->isDeleted(FALSE)->all();
            $options = ['data-pjax' => 0, 'title' => Yii::t('app', 'ผลการประเมิน'), 'data-toggle' => 'tooltip', 'target' => '_blank'];
            
            $comname = isset($com->committee_position_id) ? $com->committeePosition->name : "";
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Yii::t('app', 'เอกสารประเมินกรรมการ {0}', [$comname]) ?> <?= $com->person->fullName; ?> <?= Html::a('<i class="glyphicon glyphicon-open-file font-size-18"></i>', ['questionnaire-answer/assessment-info', 'submissionId' => $com->submission_id, 'projectId' => $com->project_id, 'sCommitteeId' => $com->id, 'model' => $com], $options); ?></h3>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
</div><br>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">          
        <div class="panel-heading">
            <h3 class="panel-title"><?= yii::t('app', 'หนังสือแจ้งผล') ?></h3>
        </div>
              </div>
        <?php
        $ardProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $submission->getResultDocuments()
        ]);
        echo $this->renderFile('@app/views/submission/letter-result.php', [
            'submission' => $submission,
            'pjaxId' => 'result-' . $submission->id,
            'dataProvider' => $ardProvider,
        ]);
        ?> 
    </div>
</div>


