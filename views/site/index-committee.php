<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;
//$this->title = 'Bus terminal Web Application System';
?>
<body class="dashboard">

    <div class="site-index">
        <div class="page animsition">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- Widget -->
                        <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_COMMITTEE_SELECTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'committeeStatus' => \app\models\SubmissionCommittee::STATUS_PENDING]) ?>" data-pjax="0" style="text-decoration: none">

                            <div class="widget">
                                <div class="widget-content padding-35 bg-blue-400">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize"><?= yii::t('app', 'งานวิจัยใหม่ที่ต้องตอบรับ') ?></span>
                                            <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, NULL, null, null, \Yii::$app->user->identity->person->id, \app\models\SubmissionCommittee::STATUS_PENDING); ?></button>
                                            <?= yii::t('app', 'โครงการ'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Widget -->
                    </div>
                    <div class="col-sm-6">
                        <!-- Widget -->
                        <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_COMMITTEE_SELECTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'committeeStatus' => \app\models\SubmissionCommittee::STATUS_PENDING]) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-35 bg-blue-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize"><?= yii::t('app', 'งานวิจัยต่อเนื่องที่ต้องตอบรับ') ?></span>
                                            <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, NULL, null, null, \Yii::$app->user->identity->person->id, \app\models\SubmissionCommittee::STATUS_PENDING); ?></button>
                                            <?= yii::t('app', 'โครงการ'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Widget -->
                    </div>
                    <div class="col-sm-6">
                        <!-- Widget -->
                        <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_COMMITTEE_ACCEPTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'committeeStatus' => \app\models\SubmissionCommittee::STATUS_ACCEPTED]) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-35 bg-red-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize"><?= yii::t('app', 'งานวิจัยใหม่ที่ต้องประเมิน') ?></span>
                                            <button type="button" class="btn btn-icon bg-red-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, NULL, null, null, \Yii::$app->user->identity->person->id, \app\models\SubmissionCommittee::STATUS_ACCEPTED); ?></button>
                                            <?= yii::t('app', 'โครงการ'); ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Widget -->
                    </div>
                    <div class="col-sm-6">
                        <!-- Widget -->
                        <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_COMMITTEE_ACCEPTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'committeeStatus' => \app\models\SubmissionCommittee::STATUS_ACCEPTED]) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-35 bg-red-400">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize"><?= yii::t('app', 'งานวิจัยต่อเนื่องที่ต้องประเมิน') ?></span>
                                            <button type="button" class="btn btn-icon bg-red-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, NULL, null, null, \Yii::$app->user->identity->person->id, \app\models\SubmissionCommittee::STATUS_ACCEPTED); ?></button>
                                            <?= yii::t('app', 'โครงการ'); ?>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </a>
                        <!-- End Widget -->
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= yii::t('app', 'งานวิจัยที่ผ่านการประเมินแล้ว') ?></h2>
                </div>
                <div class="panel-body">
                    <br>
                    <?= $this->render('/submission/_search-show', ['searchModel' => $searchModel]) ?>
                    <div class="col-md-12">                 

                        <?php
                        $currentRole = Yii::$app->session->get('currentRole');
                        $items1 = [];
                        foreach ($currentRole['newPanels'] as $p) {
                            $items1[] = [
                                'label' => $p[Yii::$app->util->getI18nAttribute('name')],
                                'content' => $this->render('/submission/index-show', [
                                    'panelId' => $p['id'],
                                    'searchModel' => $searchModel,
                                    'dataProvider' => $dataProviders[$p['id']],
                                ]),
                            ];
                        }
//                        foreach ($panels as $panelId => $panelName) {
//                            $items1[] = [
//                                'label' => $panelName,
//                                'content' => $this->render('/submission/index-show', [
//                                    'searchModel' => $searchModel,
//                                    'dataProvider' => $dataProviders[$panelId],
//                                    'panelId' => $panelId
//                                ]),
//                            ];
//                        }
                        echo Tabs::widget([
                            'itemOptions' => [
                                'class' => 'padding-top-15'
                            ],
                            'items' => $items1
                        ]);
                        ?>
                    </div>        

                </div>
            </div>
        </div>

        <div class="page-content container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= yii::t('app', 'งานประชุม') ?></h2>
                </div>
                <div class="panel-body">
                    <br>
                    <div class="col-md-12">                 
                        <?= $this->render('/meeting/index') ?>
                    </div>        

                </div>
            </div>
        </div>

    </div>
</body>
