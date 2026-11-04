<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Meeting */
$this->title = Yii::t('app', 'ออกรายงานค่าตอบแทนการอ่านพิจารณาโครงการวิจัย');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายงาน'), 'url' => ['site/report-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-update">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="nav-tabs-horizontal">
                <ul class="nav nav-tabs nav-tabs-line margin-right-25" data-plugin="nav-tabs" role="tablist">
                    <li class="active" role="presentation">
                        <a data-toggle="tab" href="#agenda" aria-controls="agenda"
                           role="tab"><?= Yii::t('app', 'วาระการประชุม') ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content padding-vertical-15">
                    <div class="tab-pane active" id="agenda" role="tabpanel">
                        <?=
                        $this->render('_agenda-report', [
                            'meeting' => $model,
                        ]);
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
