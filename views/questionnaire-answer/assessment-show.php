<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\QuestionnaireTitle;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Tambon */
$this->title = Yii::t('app', 'ประเมินงานวิจัย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายการโครงการวิจัย'), 'url' => ['submission/index', 'committeeStatus' => app\models\SubmissionCommittee::STATUS_ACCEPTED]];
//$this->params['breadcrumbs'][] = ['label' => 'เลือกประเภทโครงการวิจัย', 'url' => ['document-submission-type/index', 'roleId' => $roleId]];
$this->params['breadcrumbs'][] = $this->title;


?>

<div class="assessment">
    <div class="panel">
        <div class="panel-body">

            <?php
            //echo Html::errorSummary([$answers]);

            $upload = $this->renderFile('@app/views/submission-committee-document/index-committee-show.php', [
//                'project' => $project,
                'submission' => $submission,
                'submissonDocs' => $submissionDocs,
                'submissionDoc' => $submissionDoc,
                'dataProvider' => $subDocProvider,
//                'reloadUrl' => ['questionnaire-answer/assessment', 'submissionId' => $submission->id, 'sCommitteeId' => $sCommitteeId],
            ]);
//
//            $prContentSlipDetail = $this->renderFile('@app/views/slip-detail/index.php', [
//                'searchModel' => $prSearchSlipDetail,
//                'dataProvider' => $prProviderSlipDetail,
//                'meeting' => $model,
//            ]);


            echo Tabs::widget([
                'itemOptions' => [
                    'class' => 'padding-top-15'
                ],
                'items' => [
                        [
                        'label' => 'กรอกแบบประเมินงานวิจัย',
                        'content' => $this->render('assessment-form-show', [
                            'submission' => $submission,
                            'answers' => $answers,
                            'sCommitteeId' => $sCommitteeId,
                        ]),
                        'active' => true
                    ],
                        [
                        'label' => 'แนบไฟล์แบบประเมิน',
                        'content' => $upload,
                    ],
                ]
            ]);
            ?>
        </div>
    </div></div>
