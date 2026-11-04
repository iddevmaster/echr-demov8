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
                        <a href="<?= Url::to(['meeting/staff-check', 'status' => app\models\Meeting::CS_PRE_CHECKED]) ?>" data-pjax="0" style="text-decoration: none">
                            <div class="widget">
                                <div class="widget-content padding-35 bg-teal-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize"><?= yii::t('app', 'การประชุมที่ต้องตรวจสอบ'); ?></span>
                                            <button type="button" class="btn btn-icon bg-teal-200 btn-round btn-floating waves-effect waves-round waves-light"><?=  \Yii::$app->user->identity->getMeetingCount(app\models\Meeting::CS_PRE_CHECKED); ?> </button>
                                                <?= yii::t('app', 'การประชุม'); ?>
                                        </div>
                                    </div>
                                </div>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title"><?= yii::t('app', 'งานวิจัยทั้งหมด') ?></h2>
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
//                            foreach ($panels as $panelId => $panelName) {
//                                $items1[] = [
//                                    'label' => $panelName,
//                                    'content' => $this->render('/submission/index-show', [
//                                        'searchModel' => $searchModel,
//                                        'dataProvider' => $dataProviders[$panelId],
//                                        'panelId' => $panelId
//                                    ]),
//                                ];
//                            }
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
