<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;

app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;
//$this->title = 'Bus terminal Web Application System';
?>
<body class="dashboard">

    <div class="site-index">
        <div class="page animsition">
            <div class="page-content container-fluid">
                <?php if (Yii::$app->util->checkPermission('index.panel.staff')): ?>

                    <div class="row">
                        <div class="col-sm-6">
                            <!-- Widget -->
                            <div class="widget">
                                <div class="widget-content padding-20 bg-blue-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize font-size-20"><?= yii::t('app', 'วิจัยใหม่ยังไม่เลือก Panel'); ?></span>
                                            <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_NEW, app\models\Submission::STATUS_SUBMITTED); ?> </button>
                                            โครงการ
                                        </div>

                                        <div class="counter-label text-capitalize font-size-12 ">                                               
                                            <?=
                                            Html::a(yii::t('app', 'คลิกที่นี่เพื่อดูรายการทั้งหมด'), ['submission/index', 'status' => app\models\Submission::STATUS_SUBMITTED,'typeGroup'=>\app\models\SubmissionTypeGroup::GROUP_NEW], [
                                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                'class' => ' yellow-50',
                                                'data-toggle' => 'tooltip'])
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Widget -->
                        </div>
                        <div class="col-sm-6">
                            <!-- Widget -->
                            <div class="widget">
                                <div class="widget-content padding-20 bg-purple-600">
                                    <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                    <div class="counter counter-md counter-inverse text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-number-related text-capitalize font-size-20"><?= yii::t('app', 'วิจัยต่อเนื่องยังไม่เลือกเจ้าหน้าที่') ?></span>
                                            <button type="button" class="btn btn-icon bg-purple-200 btn-round btn-floating waves-effect waves-round waves-light"><?= \Yii::$app->user->identity->getSubmissionCount(\app\models\SubmissionTypeGroup::GROUP_CONT, app\models\Submission::STATUS_SUBMITTED); ?> </button> โครงการ

                                        </div>
                                        <div class="counter-label text-capitalize font-size-12 ">                                               
                                            <?=
                                            Html::a(yii::t('app', 'คลิกที่นี่เพื่อดูรายการทั้งหมด'), ['submission/index', 'status' => app\models\Submission::STATUS_SUBMITTED,'typeGroup'=>\app\models\SubmissionTypeGroup::GROUP_CONT], [
                                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                'class' => ' yellow-50',
                                                'data-toggle' => 'tooltip'])
                                            ?>
                                        </div>                                    </div>
                                </div>
                            </div>
                            <!-- End Widget -->
                        </div>
                    </div>
                    <div class="row" >


                        <?php
                        $currentRole = Yii::$app->session->get('currentRole');
                        $items = [];
                        foreach ($currentRole['panels'] as $panelId => $panelName) {
                            $items[] = [
                                'label' => $panelName,
                                'content' => $this->render('submission-summary', [
                                    'panelId' => $panelId,
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

                    <div class="clearfix"></div>
                    <div class="col-xlg-7 col-md-8">
                        <!-- Panel Projects Status -->
                        <div class="panel" id="projects-status">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    งานประชุมล่าสุด
                                    <span class="badge badge-info">วันอังคาร 5 ธันวาคม 2560</span>
                                </h3>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <td>#</td>
                                            <td>การประชุมครั้งที่</td>
                                            <td>วันที่ประชุม</td>
                                            <td>ดาวน์โหลดเอกสาร</td>
                                            <td>จัดการข้อมูล</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>30/2560</td>
                                            <td>
                                                <span>12/11/2560</span>
                                            </td>
                                            <td class="padding-left-50">
                                                <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="ดาวน์โหลดเอกสารประกอบการประชุม"></i></span>
                                            </td>
                                            <td class="padding-left-25">

                                                <div class="toolbar-icons hidden" id="user-options">
                                                    <a href="javascript:void(0)"  title="จัดการผู้เข้าร่วมประชุม"><i class="icon md-account" aria-hidden="true"  ></i></a>
                                                    <a href="javascript:void(0)"  title="บันทึกมติที่ประชุม"><i class="icon md-star" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"  title="จัดการวาระที่ประชุม"><i class="icon md-edit" aria-hidden="true"></i></a>
                                                </div>
                                                <button type="button" class="btn btn-icon btn-primary btn-round" data-plugin="toolbar"
                                                        data-toolbar="#user-options">
                                                    <i class="icon md-settings" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>29/2560</td>
                                            <td>
                                                <span>12/10/2560</span>
                                            </td>
                                            <td class="padding-left-50">
                                                <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="ดาวน์โหลดเอกสารประกอบการประชุม"></i></span>
                                            </td>
                                            <td class="padding-left-25">

                                                <div class="toolbar-icons hidden" id="user-options">
                                                    <a href="javascript:void(0)"><i class="icon md-account" aria-hidden="true" data-toggle="tooltip" data-original-title="จัดการผู้เข้าร่วมประชุม" ></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-star" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-edit" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-delete" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-bookmark" aria-hidden="true"></i></a>
                                                </div>
                                                <button type="button" class="btn btn-icon btn-primary btn-round" data-plugin="toolbar"
                                                        data-toolbar="#user-options">
                                                    <i class="icon md-settings" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>  
                                        <tr>
                                            <td>3</td>
                                            <td>28/2560</td>
                                            <td>
                                                <span>12/09/2560</span>
                                            </td>
                                            <td class="padding-left-50">
                                                <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="ดาวน์โหลดเอกสารประกอบการประชุม"></i></span>
                                            </td>
                                            <td class="padding-left-25">

                                                <div class="toolbar-icons hidden" id="user-options">
                                                    <a href="javascript:void(0)"><i class="icon md-account" aria-hidden="true" data-toggle="tooltip" data-original-title="จัดการผู้เข้าร่วมประชุม" ></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-star" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-edit" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-delete" aria-hidden="true"></i></a>
                                                    <a href="javascript:void(0)"><i class="icon md-bookmark" aria-hidden="true"></i></a>
                                                </div>
                                                <button type="button" class="btn btn-icon btn-primary btn-round" data-plugin="toolbar"
                                                        data-toolbar="#user-options">
                                                    <i class="icon md-settings" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr> 
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="text-right"><i class="icon md-plus btn-lg" aria-hidden="true"></i> ข้อมูลทั้งหมด</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- End Panel Projects Stats -->
                    <?php endif; ?>

                </div>
                <?php if (Yii::$app->util->checkPermission('site.admin.can')): ?>

                    <div class="col-lg-4 col-md-6"> 
                        <!-- Example Tabs In The Panel -->
                        <div class="panel nav-tabs-horizontal">
                            <div class="panel-heading">
                                <h3 class="panel-title">การประชุมล่าสุด : <br>ครั้งที่ 10/2560 วันที่ 12 ธันวาคม 2560</h3>
                            </div>
                            <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                <li class="active" role="presentation"><a data-toggle="tab" href="#panel1" aria-controls="panel1"
                                                                          role="tab">
                                        <i class="icon md-home" aria-hidden="true"></i>Panel 1</a></li>
                                <li role="presentation"><a data-toggle="tab" href="#panel2" aria-controls="panel2"
                                                           role="tab">
                                        <i class="icon md-account" aria-hidden="true"></i>Panel 2</a></li>
                                <li role="presentation"><a data-toggle="tab" href="#panel3" aria-controls="panel3"
                                                           role="tab">
                                        <i class="icon md-account" aria-hidden="true"></i>Panel 3</a></li>

                            </ul>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="panel1" role="tabpanel">
                                        <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>

                                    </div>
                                    <div class="tab-pane" id="panel2" role="tabpanel">
                                        <li> วาระ 4.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.2. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.3. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.4. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.5. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.6. จำนวนงานวิจัย 5 รายการ</li>
                                    </div>
                                    <div class="tab-pane" id="panel3" role="tabpanel">
                                        <li> วาระ 4.1. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.2. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.3. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.4. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.5. จำนวนงานวิจัย 5 รายการ</li>
                                        <li> วาระ 4.6. จำนวนงานวิจัย 5 รายการ</li>
                                    </div>
                                </div>


                            </div>
                            <div class="panel-footer text-right panel panel-bordered "><i class="icon md-plus btn-lg" aria-hidden="true"></i> ข้อมูลทั้งหมด</div>

                            <!-- End Example Tabs In The Panel -->
                        </div>
                    <?php endif; ?>
                    <?php if (Yii::$app->util->checkPermission('index.panel.staff')): ?>
                        <?php if (!Yii::$app->util->checkPermission('site.cannot')): ?>

                            <div class="col-lg-4 col-md-6"> 
                                <div class="panel nav-tabs-horizontal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">โครงการวิจัยตามวาระการประชุม</h3>
                                    </div>
                                    <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                        <li class="active" role="presentation"><a data-toggle="tab" href="#exampleTopHome" aria-controls="exampleTopHome"
                                                                                  role="tab">
                                                <i class="icon md-home" aria-hidden="true"></i>Panel 1</a></li>
                                        <li role="presentation"><a data-toggle="tab" href="#exampleTopComponents" aria-controls="exampleTopComponents"
                                                                   role="tab">
                                                <i class="icon md-account" aria-hidden="true"></i>ดาวน์โหลดเอกสารแจ้งผล</a></li>


                                    </ul>
                                    <div class="panel-body ">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="exampleTopHome" role="tabpanel">
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 3.1. จำนวนงานวิจัย 5 รายการ</li>
                                            </div>
                                            <div class="tab-pane" id="exampleTopComponents" role="tabpanel">
                                                <li> วาระ 4.1. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 4.2. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 4.3. จำนวนงานวิจัย 5 รายการ</li>
                                                <li> วาระ 4.4. จำนวนงานวิจัย 5 รายการ</li>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer text-right panel panel-bordered "><i class="icon md-plus btn-lg" aria-hidden="true"></i> ข้อมูลทั้งหมด</div>


                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (Yii::$app->util->checkPermission('index.box.researcher')): ?>
                        <?php if (!Yii::$app->util->checkPermission('.site.cannot')): ?>
                            <div class="col-md-12">
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
                            <div class="col-md-12">
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
                            <div class="col-md-12">
                                <?php
                                echo $this->renderFile('@app/views/submission/index-not-isleader.php', [
                                    'searchModel' => $searchModel,
                                    'dataProvider' => $dataProvider,
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (Yii::$app->util->checkPermission('index.box.committee')): ?>
                        <?php if (!Yii::$app->util->checkPermission('site.cannot')): ?>

                            <div class="col-sm-6">
                                <!-- Widget -->
                                <div class="widget">
                                    <div class="widget-content padding-30 bg-blue-600">
                                        <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                        <div class="counter counter-md counter-inverse text-left">
                                            <div class="counter-number-group">
                                                <span class="counter-number-related text-capitalize">งานวิจัยที่ต้องพิจารณา</span>
                                                <button type="button" class="btn btn-icon bg-blue-200 btn-round btn-floating waves-effect waves-round waves-light">5</button>
                                            </div>
                                            <?=
                                            Html::a('คลิกที่นี่เพื่อเข้าไปตอบรับการอ่านงานวิจัยใหม่', ['project/index-committee'], [
                                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                'class' => ' yellow-50 counter-label text-capitalize',
                                                'data-toggle' => 'tooltip'])
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Widget -->
                            </div>
                            <div class="col-sm-6">
                                <!-- Widget -->
                                <div class="widget">
                                    <div class="widget-content padding-30 bg-red-600">
                                        <div class="widget-watermark darker font-size-60 margin-15"><i class="icon md-assignment" aria-hidden="true"></i></div>
                                        <div class="counter counter-md counter-inverse text-left">
                                            <div class="counter-number-group">
                                                <span class="counter-number-related text-capitalize">งานวิจัยที่ยังไม่ประเมิน</span>
                                                <button type="button" class="btn btn-icon bg-red-200 btn-round btn-floating waves-effect waves-round waves-light">2</button>
                                            </div>
                                            <?=
                                            Html::a('คลิกที่นี่เพื่อเข้าไปทำการประเมิน', ['project/index-committee'], [
                                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                'class' => ' yellow-50 counter-label text-capitalize',
                                                'data-toggle' => 'tooltip'])
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Widget -->
                            </div>
                            <div class="col-lg-4 col-md-6"> 
                                <!-- Example Tabs In The Panel -->
                                <div class="panel nav-tabs-horizontal">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">งานวิจัยที่ประเมินไปแล้ว</h3>
                                    </div>
                                    <ul class="nav nav-tabs nav-tabs-line" data-plugin="nav-tabs" role="tablist">
                                        <li class="active" role="presentation"><a data-toggle="tab" href="#exampleTopHome" aria-controls="exampleTopHome" role="tab" aria-expanded="true"></a></li>
                                        <li class="dropdown" role="presentation" style="display: none;">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                                <span class="caret"></span>Dropdown </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <li class="" role="presentation"><a data-toggle="tab" href="#exampleTopHome" aria-controls="exampleTopHome" role="tab"><i class="icon md-home" aria-hidden="true"></i></a></li>
                                            </ul>
                                        </li>
                                        <li class="nav-tabs-autoline" style="transition-duration: 0.5s, 1s; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1), cubic-bezier(0.4, 0, 0.2, 1); left: 0px; width: 100px;"></li></ul>
                                    <div class="panel-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="exampleTopHome" role="tabpanel">
                                                <li> 470506 : การพิเคราะห์โปรตีโอมของเชื้อเบอโคลเดอรียป์สูโดมาลิไอเพื่อหาวัคซีนแอนติเจน การพิเคราะห์โปรตีโอมของเชื้อเบอโคลเดอรียป์สูโดมาลิไอเพื่อหาวัคซีนแอนติเจน</li>
                                                <li> 470507 : กลไกการเกิดโรคมะเร็งที่เกิดจากการติดเชื้อพยาธิใบไม้ตับในประเทศไทย</li>
                                                <li> 470508 : ข้อมูลการติดเชื้อเอชไอวีในเด็กของเอเชีย</li>


                                            </div>

                                        </div>

                                    </div>
                                    <div class="panel-footer  panel-bordered text-right"><i class="icon md-plus btn-lg" aria-hidden="true"></i> ข้อมูลทั้งหมด</div>

                                </div>

                            </div>

                            <div class="col-xlg-7 col-md-8">
                                <!-- Panel Projects Status -->
                                <div class="panel" id="projects-status">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            เอกสารสำหรับกรรมการ
                                        </h3>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td>#</td>
                                                    <td>ชื่อเอกสาร</td>
                                                    <td>วันที่แก้ไขล่าสุด</td>
                                                    <td>ดาวน์โหลดเอกสาร</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>AF-08-06-03.0_แบบประเมินโครงการ Continuing</td>
                                                    <td>
                                                        <span>12/11/2560</span>
                                                    </td>
                                                    <td class="padding-left-50">
                                                        <?=
                                                        Html::a('<i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="เลือกโครงการวิจัยที่ต้องการก่อน"></i>', ['site/select-project'], ['role' => 'modal-remote',
                                                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                            //           'class' => 'btn btn-primary btn-lg',
//            'data-pjax' => FALSE,
//            'data-request-method' => 'post',
                                                            'data-toggle' => 'tooltip'])
                                                        ?>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>AF-11-03-03.2_แบบประเมินโครงการใหม่ทางคลินิก</td>
                                                    <td>
                                                        <span>12/11/2560</span>
                                                    </td>
                                                    <td class="padding-left-50">
                                                        <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="เลือกโครงการวิจัยที่ต้องการก่อน"></i></span>
                                                    </td>

                                                </tr> 
                                                <tr>
                                                    <td>3</td>
                                                    <td>AF-12-03-03.2_แบบประเมินโครงการใหม่ทางสังคมศาสตร์</td>
                                                    <td>
                                                        <span>12/11/2560</span>
                                                    </td>
                                                    <td class="padding-left-50">
                                                        <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="เลือกโครงการวิจัยที่ต้องการก่อน"></i></span>
                                                    </td>

                                                </tr> 
                                                <tr>
                                                    <td>4</td>
                                                    <td>AF-13-03-03.2_แบบฟอร์มประเมินของกรรมการ Expedited</td>
                                                    <td>
                                                        <span>12/11/2560</span>
                                                    </td>
                                                    <td class="padding-left-50">
                                                        <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="เลือกโครงการวิจัยที่ต้องการก่อน"></i></span>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td>5</td>
                                                    <td>แบบประเมินกรณีผู้วิจัยส่งแก้ไขหลังประชุม</td>
                                                    <td>
                                                        <span>12/11/2560</span>
                                                    </td>
                                                    <td class="padding-left-50">
                                                        <span data-plugin="peityLine"><i class="icon wb-download" aria-hidden="true" data-toggle="tooltip" data-original-title="เลือกโครงการวิจัยที่ต้องการก่อน"></i></span>
                                                    </td>

                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" class="text-right"><i class="icon md-plus btn-lg" aria-hidden="true"></i> ข้อมูลทั้งหมด</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Panel Projects Stats -->
                            </div>

                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
