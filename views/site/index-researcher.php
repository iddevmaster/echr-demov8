<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;
//$this->title = 'Bus terminal Web Application System';
$user = \Yii::$app->user->identity->person->id;
$countRe = app\models\ProjectResearcher::find()->joinWith('submission')->person(\Yii::$app->user->identity->person->id)->isDeleted(false)->isDeletedSubmission(false)->isLeader(false)->count();
$countConsult = app\models\ProjectConsultant::find()->joinWith('submission')->person(\Yii::$app->user->identity->person->id)->isDeleted(false)->isDeletedSubmission(false)->count();
?>
<style>
    .link-card {
        display: block;
        text-decoration: none;
    }

    .link-card .alert {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none;
    }

    /* ✨ hover ยกขึ้น */
    .link-card:hover .alert {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* ✨ glow นิด ๆ */
    .link-card:hover .alert-primary {
        background-color: #8b311e;
        color: #fff;
    }

    /* ✨ icon ขยับ */
    .link-card .icon {
        transition: transform 0.3s ease;
    }

    .link-card:hover .icon {
        transform: translateX(6px) scale(1.1);
    }

    /* ✨ shine effect */
    .link-card .alert::before {
        content: "";
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(
            120deg,
            transparent,
            rgba(255,255,255,0.4),
            transparent
            );
        transform: skewX(-25deg);
    }

    .link-card:hover .alert::before {
        animation: shine 0.8s;
    }
    .link-card:active .alert {
        transform: scale(0.97);
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    }
    @keyframes shine {
        100% {
            left: 125%;
        }
    }
</style>
<body class="dashboard">

    <div class="site-index">
        <div class="page animsition">
            <?php if (\Yii::$app->user->identity->getHasApprovePendingSubmissionFromCo()) { ?>
                <div class="row">
                    <div class="col-md-4">  
                        <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'isLegacy' => 2]) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-25 bg-blue-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize font-size-18"><?= yii::t('app', 'วิจัยใหม่ยังรอการยืนยันจากหัวหน้าโครงการ'); ?></span><br>
                                            <div class="counter-number-related text-capitalize font-size-18"><?= yii::t('app', 'จำนวน'); ?> <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, NULL, NULL, NULL, NULL, NULL, 0); ?></button>
                                                <?= yii::t('app', 'โครงการ'); ?></div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>
                    <div class="col-md-4">   
                        <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'isLegacy' => 1]) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-25 bg-primary-800">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize font-size-18"><?= yii::t('app', 'วิจัยที่รับรองแล้วรอการยืนยันจากหัวหน้าโครงการ'); ?></span><Br>
                                            <div class="counter-number-related text-capitalize font-size-18"><?= yii::t('app', 'จำนวน'); ?> <button type="button" class="btn btn-icon bg-primary-300 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER, NULL, NULL, NULL, NULL, NULL, 1); ?> </button>
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
                                            <span class="counter-number-related text-capitalize font-size-18"><?= yii::t('app', 'วิจัยต่อเนื่องยังรอการยืนยันจากหัวหน้าโครงการ'); ?></span><br>
                                            <div class="counter-number-related text-capitalize font-size-18"><?= yii::t('app', 'จำนวน'); ?> <button type="button" class="btn btn-icon bg-yellow-700 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER); ?> </button>
                                                <?= yii::t('app', 'โครงการ'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>

            <div class="page-content container-fluid">
                <div class="row">
                    <div class="example-wrap">
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index-not-isleader']) ?>" data-pjax="0" style="text-decoration: none" target="_blank">
                                <button type="button" class="btn btn-outline btn-warning text-left " style="width: 100%"> <i class="icon md-account-add font-size-24" aria-hidden="true"></i> ดูงานวิจัยที่เป็นผู้ร่วมวิจัย  <?= $countRe ?> <?= yii::t('app', 'โครงการ'); ?></button>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index-isconsultant']) ?>" data-pjax="0" style="text-decoration: none" target="_blank">
                                <button type="button" class="btn btn-outline btn-danger text-left"  style="width: 100%"> <i class="icon md-account-o font-size-24" aria-hidden="true"></i> ดูงานวิจัยที่เป็นที่ปรึกษา <?= $countConsult ?> <?= yii::t('app', 'โครงการ'); ?></button>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_PENDING_SUBMISSION, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'isLegacy' => 2, 'accept' => 1]) ?>" data-pjax="0" style="text-decoration: none">
                                <button type="button" class="btn btn-outline btn-success text-left"  style="width: 100%"> <i class="icon md-accounts-add font-size-24" aria-hidden="true"></i> <?= yii::t('app', 'งานวิจัยที่รอการตอบรับเป็นผู้ร่วมวิจัย'); ?>  <?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1); ?>  <?= yii::t('app', 'โครงการ'); ?></button>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_PENDING_SUBMISSION, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'isLegacy' => 2, 'accept' => 2]) ?>" data-pjax="0" style="text-decoration: none">
                                <button type="button" class="btn btn-outline btn-info text-left"  style="width: 100%"> <i class="icon md-accounts-alt font-size-24" aria-hidden="true"></i> <?= yii::t('app', 'งานวิจัยที่รอการตอบรับเป็นที่ปรึกษา'); ?>  <?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2); ?> <?= yii::t('app', 'โครงการ'); ?></button>
                            </a>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="example-wrap">

                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/new']) ?>" data-pjax="0" style="text-decoration: none" target="_blank" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible" >
                                    <i class="icon md-mail-send" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'ส่งเอกสารโครงการวิจัยใหม่'); ?></h4>
                                    <p style="height:50px;"><?= yii::t('app', 'เริ่มต้นการขอพิจารณาครั้งแรก'); ?></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index-resubmission-new']) ?>" data-pjax="0" style="text-decoration: none" target="_blank" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible" >
                                    <i class="icon md-border-color" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'ส่งแก้ไขโครงการวิจัยใหม่'); ?></h4>
                                    <p style="height:50px;"><?= yii::t('app', 'เอกสารไม่ครบถ้วน/ไม่ถูกต้อง,แก้ไขมติ C , มติ R'); ?></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/continue']) ?>" data-pjax="0" style="text-decoration: none" target="_blank" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible" >
                                    <i class="icon md-comment-list" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'ส่งเอกสารโครงการวิจัยต่อเนื่อง'); ?></h4>
                                    <p style="height:50px;"><?= yii::t('app', 'ขอต่ออายุ , แจ้งเบี่ยงเบน , ปรับปรุงโครงการ , SAE , แจ้งปิด'); ?></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index-resubmission-cont']) ?>" data-pjax="0" style="text-decoration: none" target="_blank" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible">
                                    <i class="icon md-comment-edit" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'ส่งแก้ไขโครงการวิจัยต่อเนื่อง'); ?></h4>
                                    <p style="height:50px;"><?= yii::t('app', 'เอกสารไม่ครบถ้วน/ไม่ถูกต้อง,แก้ไขมติ C , มติ R'); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

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
                        <?=
                        $this->render('/submission/index-isresearcher', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ])
                        ?>
                    </div>        

                </div>
            </div>
        </div>

    </div>

</body>
