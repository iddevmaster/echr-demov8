<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use yii\helpers\Url;

app\assets\ToolbarJsAsset::register($this);
$this->title = Yii::$app->name;
//$this->title = 'Bus terminal Web Application System';
?>

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
                    
                </div>
            </div>
        </div>
        
    </div>

<?php
$js = <<<js
    var h = $('.page-header').innerHeight() - 60;
//    console.log($('.page-header').innerHeight());
//    console.log(h);
    
    $('.page-content').css('padding-top', h+'px');
js;
$this->registerJs($js);
