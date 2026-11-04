<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;
//$this->title = 'Bus terminal Web Application System';

$submissionMonitor = \app\models\Submission::find()->isDeleted(false)->projectViewer(\Yii::$app->user->id)->one();
$submissionMonitorCount = \app\models\Submission::find()->isDeleted(false)->projectViewer(\Yii::$app->user->id)->coiPerson(\Yii::$app->user->identity->person->id)->count();
?>
<body class="dashboard">

    <div class="site-index">

        <div class="page animsition">
            <?php if (isset($submissionMonitor)) { ?>
                <div class="row">
                    <div class="col-md-4">  
                        <a href="<?= Url::to(['submission/index-ismonitor']) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-25 bg-green-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment-check" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'Submission ที่มีถูกกำหนดเป็น Monitor'); ?></span><Br>
                                            <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-green-200 btn-round btn-floating waves-effect waves-round waves-light"><?= $submissionMonitorCount; ?></button>
                                                <?= yii::t('app', 'Submission'); ?></div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>     
                </div>

            <?php } ?>
            <div class="row">

                <div class="col-md-4">  
                    <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW]) ?>" data-pjax="0" style="text-decoration: none">
                        <div class="widget">
                            <div class="widget-content padding-25 bg-blue-600">
                                <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                <div class="counter counter-md counter-inverse text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'วิจัยใหม่รอการยืนยันจากหัวหน้าโครงการ'); ?></span><Br>
                                        <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER); ?></button>
                                            <?= yii::t('app', 'โครงการ'); ?></div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </a>
                </div>
                <div class="col-md-4">   
                    <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW]) ?>" data-pjax="0" style="text-decoration: none">
                        <div class="widget">
                            <div class="widget-content padding-25 bg-primary-800">
                                <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                <div class="counter counter-md counter-inverse text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'วิจัยที่รับรองแล้วรอการยืนยันจากหัวหน้าโครงการ'); ?></span><Br>
                                        <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-primary-300 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, NULL, NULL, NULL, NULL, NULL, 1); ?> </button>
                                            <?= yii::t('app', 'โครงการ'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">   
                    <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT]) ?>" data-pjax="0" style="text-decoration: none">
                        <div class="widget">
                            <div class="widget-content padding-25 bg-yellow-800">
                                <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                <div class="counter counter-md counter-inverse text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'วิจัยต่อเนื่องรอการยืนยันจากหัวหน้าโครงการ'); ?></span><br>
                                        <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-yellow-700 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER); ?> </button>
                                            <?= yii::t('app', 'โครงการ'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">  
                    <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW]) ?>" data-pjax="0" style="text-decoration: none">
                        <div class="widget">
                            <div class="widget-content padding-25 bg-blue-300">
                                <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                <div class="counter counter-md counter-inverse text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'วิจัยใหม่ไม่ผ่านการตรวจสอบจากหัวหน้าโครงการ'); ?></span><Br>
                                        <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-blue-600 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER); ?></button>
                                            <?= yii::t('app', 'โครงการ'); ?></div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </a>
                </div>
                <div class="col-md-4">   
                    <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW]) ?>" data-pjax="0" style="text-decoration: none">
                        <div class="widget">
                            <div class="widget-content padding-25 bg-primary-300">
                                <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                <div class="counter counter-md counter-inverse text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'วิจัยที่รับรองแล้วไม่ผ่านการตรวจสอบจากหัวหน้าโครงการ'); ?></span><br>
                                        <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-primary-800 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER, NULL, NULL, NULL, NULL, NULL, 1); ?> </button>
                                            <?= yii::t('app', 'โครงการ'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">   
                    <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT]) ?>" data-pjax="0" style="text-decoration: none">
                        <div class="widget">
                            <div class="widget-content padding-25 bg-yellow-700">
                                <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                <div class="counter counter-md counter-inverse text-left">
                                    <div class="counter-number-group">
                                        <span class="counter-number-related text-capitalize font-size-16"><?= yii::t('app', 'วิจัยต่อเนื่องที่ไม่ผ่านการตรวจสอบจากหัวหน้าโครงการ'); ?></span><br>
                                        <div class="counter-number-related text-capitalize font-size-16">จำนวน <button type="button" class="btn btn-icon bg-yellow-800 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER); ?> </button>
                                            <?= yii::t('app', 'โครงการ'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="page-content container-fluid">
                <?php if (Yii::$app->util->checkPermission('index.box.researcher')): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $this->renderFile('@app/views/site/_listnew.php', ['panelId' => NULL]);
                            ?>
                        </div>

                        <div class="col-md-6">
                            <?php
                            echo $this->renderFile('@app/views/site/_listcontinue.php', ['panelId' => NULL]);
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <?php
                            echo $this->renderFile('@app/views/site/_listnewmeeting.php', ['panelId' => NULL]);
                            ?>
                        </div>

                        <div class="col-md-6">
                            <?php
                            echo $this->renderFile('@app/views/site/_listcontinuemeeting.php', ['panelId' => NULL]);
                            ?>
                        </div>
                    </div>


                <?php endif; ?>
            </div>
        </div>
                <div class="page-content container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= yii::t('app', 'งานวิจัยทั้งหมด') ?></h2>
                </div>
                <div class="panel-body">
                    <br>
                    <div class="col-md-12">                 
                        <?= $this->render('/submission/index-isresearcher', [
                                            'searchModel' => $searchModel,
                                            'dataProvider' => $dataProvider,
                                        ]) ?>
                    </div>        

                </div>
            </div>
        </div>
    </div>

</body>
