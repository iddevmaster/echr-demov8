<?php

use yii\helpers\Html;
\app\assets\HotkeysAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Meeting */
$this->title = Yii::t('app', 'ข้อมูลการประชุม');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-view">

    <div class="panel panel-default">
        <div class="panel-body">

            <div class="nav-tabs-horizontal">
                <ul class="nav nav-tabs nav-tabs-line margin-right-25" data-plugin="nav-tabs" role="tablist">

                    <li class="active" role="presentation">
                        <a data-toggle="tab" href="#files" aria-controls="files"
                           role="tab"><?= Yii::t('app', 'เอกสารประกอบการประชุม') ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content padding-vertical-15">

                    <div class="tab-pane active" id="files" role="tabpanel">
                        <?=
                        $this->render('_meeting-files', [
                            'meeting' => $model,
                        ]);
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
