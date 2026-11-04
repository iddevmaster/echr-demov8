<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

//app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;

$url1 = ['submission/index', 'status' => app\models\Submission::STATUS_SUBMITTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'isLegacy' => 2, 'hasProjectCode' => 1];
$base64url1 = base64_encode(\yii\helpers\Url::to($url1));
$url1['url1'] = $base64url1;

$url2 = ['submission/index', 'status' => app\models\Submission::STATUS_SUBMITTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW, 'isLegacy' => 1];
$base64url2 = base64_encode(\yii\helpers\Url::to($url2));
$url2['url2'] = $base64url2;

$url = ['submission/index', 'status' => app\models\Submission::STATUS_SUBMITTED, 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT];
$base64url = base64_encode(\yii\helpers\Url::to($url));
$url['url'] = $base64url;



?>
<body class="dashboard">
    <div class="site-index">
        <div class="page animsition">
            <div class="page-content container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <!-- Widget -->
                        <div class="row">
                            <div class="col-sm-6"><a href="<?= Url::to($url1) ?>" data-pjax="0" style="text-decoration: none">
                                    <div class="widget">
                                        <div class="widget-content padding-10 bg-blue-600" style="height: 165px;">
                                            <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                            <div class="counter counter-md counter-inverse text-left">
                                                <div class="counter-number-group">
                                                    <span class="counter-number-related text-capitalize font-size-20"><?= yii::t('app', 'วิจัยใหม่ยังไม่เลือก Panel'); ?></span><Br>
                                                    <div class="counter-number-related text-capitalize font-size-20">จำนวน <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionNewCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_SUBMITTED, 2); ?> </button>
                                                        <?= yii::t('app', 'โครงการ'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a></div>
                            <div class="col-sm-6"><a href="<?= Url::to($url2) ?>" data-pjax="0" style="text-decoration: none">
                                    <div class="widget">
                                        <div class="widget-content padding-10 bg-primary-600" style="height: 165px;">
                                            <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                            <div class="counter counter-md counter-inverse text-left">
                                                <div class="counter-number-group">
                                                    <span class="counter-number-related text-capitalize font-size-20"><?= yii::t('app', 'วิจัยใหม่ที่รับรองแล้วรอยืนยัน'); ?></span><br>
                                                    <div class="counter-number-related text-capitalize font-size-20">จำนวน <button type="button" class="btn btn-icon bg-primary-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionNewCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_SUBMITTED, 1); ?> </button>
                                                        <?= yii::t('app', 'โครงการ'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="<?= Url::to($url) ?>" data-pjax="0" style="text-decoration: none">
                                    <div class="widget">
                                        <div class="widget-content padding-15 bg-purple-600" style="height: 165px;">
                                            <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                            <div class="counter counter-md counter-inverse text-left">
                                                <div class="counter-number-group">
                                                    <span class="counter-number-related text-capitalize font-size-20"><?= yii::t('app', 'วิจัยต่อเนื่องที่ยังไม่เลือกผู้รับผิดชอบ'); ?></span><Br>
                                                    <div class="counter-number-related text-capitalize font-size-20">จำนวน <button type="button" class="btn btn-icon bg-purple-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionContinueCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_SUBMITTED); ?> </button>
                                                        <?= yii::t('app', 'โครงการ'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a></div>
                            <div class="col-sm-6">
                                <a href="<?= Url::to(['meeting/staff-check', 'status' => app\models\Meeting::CS_PENDING]) ?>" data-pjax="0" style="text-decoration: none">
                                    <div class="widget">
                                        <div class="widget-content padding-15 bg-green-600" style="height: 165px;">
                                            <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                            <div class="counter counter-md counter-inverse text-left">
                                                <div class="counter-number-group">
                                                    <span class="counter-number-related text-capitalize font-size-20"><?= yii::t('app', 'การประชุมที่ต้องตรวจสอบ'); ?></span><Br>
                                                    <div class="counter-number-related text-capitalize font-size-20">จำนวน <button type="button" class="btn btn-icon bg-green-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getMeetingCount(app\models\Meeting::CS_PENDING); ?> </button>
                                                        <?= yii::t('app', 'โครงการ'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>


                        <!-- End Widget -->
                    </div>
                    <div class="col-sm-6">

                        <?php
                        echo $this->renderFile('@app/views/site/_listnew-staff-no-panel.php', ['panelId' => NULL]);
                        ?>

                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title"> <?= yii::t('app', 'โครงการวิจัยใหม่ที่เจ้าหน้าที่ส่งแทนผู้วิจัย') ?></h3>
                            </div>
                            <div class="panel-body">
                                <ul class="list-group list-group-full">
                                    <li class="list-group-item">
                                        <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_PENDING_SUBMISSION); ?>  <?= yii::t('app', 'โครงการ') ?></span>
                                        <?=
                                        Html::a(yii::t('app', 'โครงการที่ยังไม่แล้วเสร็จ'), ['submission/index', 'status' => app\models\Submission::STATUS_PENDING_SUBMISSION,'typeGroup'=>\app\models\SubmissionTypeGroup::GROUP_NEW], [
                                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                            'data-toggle' => 'tooltip'])
                                        ?> </li>
                                    <li class="list-group-item">
                                        <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER); ?>  <?= yii::t('app', 'โครงการ') ?></span>
                                        <?=
                                        Html::a(yii::t('app', 'โครงการวิจัยใหม่รอการยืนยันจากหัวหน้าโครงการ'), ['submission/index', 'status' => app\models\Submission::STATUS_WAITING_APPROVE_PROJECT_RESEARCHER], [
                                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                            'data-toggle' => 'tooltip'])
                                        ?></li>
                                    <li class="list-group-item">
                                        <span class="badge badge-success"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER); ?>  <?= yii::t('app', 'โครงการ') ?></span>
                                        <?=
                                        Html::a(yii::t('app', 'โครงการวิจัยใหม่ไม่ผ่านการตรวจสอบจากหัวหน้าโครงการ'), ['submission/index', 'status' => app\models\Submission::STATUS_NOT_APPROVE_PROJECT_RESEARCHER], [
                                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                            'data-toggle' => 'tooltip'])
                                        ?></li>
                                </ul>

                            </div>
                        </div>
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
//                                $panels = $currentRole['panels'];
//                                foreach ($panels as $panelId => $panelName) {
//                                    $items1[] = [
//                                        'label' => $panelName,
//                                        'content' => $this->render('/submission/index-show', [
//                                            'searchModel' => $searchModel,
//                                            'dataProvider' => $dataProviders[$panelId],
//                                            'panelId' => $panelId
//                                        ]),
//                                    ];
//                                }
                                foreach ($currentRole['newPanels'] as $p) {
                                    $items1[] = [
                                        'label' => $p[Yii::$app->util->getI18nAttribute('name')],
                                        'content' => $this->render('/submission/index-show', [
                                            'searchModel' => $searchModel,
                                            'dataProvider' => $dataProviders[$p['id']],
                                            'panelId' => $p['id']
                                        ]),
                                    ];
                                }
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
        </div>
    </div>
</body>
