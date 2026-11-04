<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

//app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;
//$this->title = 'Bus terminal Web Application System';
$currentRole = Yii::$app->session->get('currentRole');
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
    .alert-dismissible {
        padding-right: 10px;
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
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="example-wrap">
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_SECRETARY_SELECT_TYPE, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'secretary' => 1]) ?>" data-pjax="0" style="text-decoration: none" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible" >
                                    <i class="icon glyphicon glyphicon-leaf" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'งานวิจัยใหม่'); ?></h4>
                                    <p style="height:50px;">
                                        <?= yii::t('app', ' รอกำหนดประเภทการพิจารณา'); ?> <?= yii::t('app', 'จำนวน'); ?> <span class="badge badge-default" ><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_SECRETARY_SELECT_TYPE, NULL, NULL, NULL, NULL, \Yii::$app->user->identity->id); ?> </span> <?= yii::t('app', 'โครงการ'); ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_SECRETARY_SELECTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'secretary' => 1]) ?>" data-pjax="0" style="text-decoration: none" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible" >
                                    <i class="icon glyphicon glyphicon-tree-conifer" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'งานวิจัยใหม่'); ?></h4>
                                    <p style="height:50px;"><?= yii::t('app', 'รอกำหนดกรรมการ'); ?> <?= yii::t('app', 'จำนวน'); ?> <span class="badge badge-default" ><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_SECRETARY_SELECTED, NULL, NULL, NULL, NULL, \Yii::$app->user->identity->id); ?> </span> <?= yii::t('app', 'โครงการ'); ?></p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_SECRETARY_SELECT_TYPE, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'secretary' => 1]) ?>" data-pjax="0" style="text-decoration: none" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible" >
                                    <i class="icon glyphicon glyphicon-tree-deciduous" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'งานวิจัยต่อเนื่อง'); ?></h4>
                                    <p style="height:50px;">
                                        <?= yii::t('app', 'รอกำหนดประเภทการพิจารณา'); ?> <?= yii::t('app', 'จำนวน'); ?> <span class="badge badge-default" ><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_SECRETARY_SELECT_TYPE, NULL, NULL, NULL, NULL, \Yii::$app->user->identity->id); ?> </span> <?= yii::t('app', 'โครงการ'); ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="<?= Url::to(['submission/index', 'status' => app\models\Submission::STATUS_SECRETARY_SELECTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT, 'secretary' => 1]) ?>" data-pjax="0" style="text-decoration: none" class="link-card">
                                <div role="alert" class="alert alert-social alert-primary  alert-dismissible">
                                    <i class="icon glyphicon glyphicon-cloud" aria-hidden="true"></i>
                                    <h4><?= yii::t('app', 'งานวิจัยต่อเนื่อง'); ?></h4>
                                    <p style="height:50px;"><?= yii::t('app', 'รอกำหนดกรรมการ'); ?> <?= yii::t('app', 'จำนวน'); ?> <span class="badge badge-default" ><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_SECRETARY_SELECTED, NULL, NULL, NULL, NULL, \Yii::$app->user->identity->id); ?> </span> <?= yii::t('app', 'โครงการ'); ?></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <a href="<?= Url::to(['meeting/staff-check', 'status' => app\models\Meeting::CS_STAFF_CHECKED]) ?>" data-pjax="0" style="text-decoration: none" class="link-card">

                            <div role="alert" class="alert alert-social alert-warning  alert-dismissible">
                                <i class="icon glyphicon glyphicon-file" aria-hidden="true"></i>
                                <h4><?= yii::t('app', 'การประชุมที่ต้องตรวจสอบ'); ?></h4>
                                <p style="height:50px;"><?= yii::t('app', 'รายงานการประชุมที่ต้องตรวจสอบความถูกต้องของมติที่ประชุม'); ?> <?= yii::t('app', 'จำนวน'); ?> <span class="badge badge-default" ><?= \Yii::$app->user->identity->getMeetingCount(app\models\Meeting::CS_STAFF_CHECKED); ?> </span> <?= yii::t('app', 'รายงาน'); ?></p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row" >


                    <?php
                    $currentRole = Yii::$app->session->get('currentRole');
                    $items = [];
//                    yii\helpers\VarDumper::dump($currentRole, 10, TRUE);
//                    foreach ($currentRole['panels'] as $panelId => $panelName) {
//                        $items[] = [
//                            'label' => $panelName,
//                            'content' => $this->render('submission-summary', [
//                                'panelId' => $panelId,
//                            ]),
//                        ];
//                    }
                    foreach ($currentRole['newPanels'] as $p) {
                        $items[] = [
                            'label' => $p[Yii::$app->util->getI18nAttribute('name')],
                            'content' => $this->render('submission-summary', [
                                'panelId' => $p['id'],
                            ]),
                        ];
                    }
                    echo Tabs::widget([
                        'itemOptions' => [
                            'class' => 'padding-top-15'
                        ],
                        'items' => $items
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="page-content container-fluid">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title"><?= yii::t('app', 'งานวิจัยทั้งหมด') ?></h2>
                </div>
                <div class="panel-body">
                    <br>
                    <?= $this->render('/submission/_search-show', ['searchModel' => $searchModel]) ?>
                    <div class="col-md-12">                 

                        <?php
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

    </div>
</body>
