<?php

//
/* @var $this yii\web\View */

use yii\helpers\Html;

//$this->title = 'About';
//$this->params['breadcrumbs'][] = $this->title;
//echo $panelId;
?>
<div class="col-lg-12">
    <div class="col-md-6">
        <?php
        echo $this->renderFile('@app/views/site/_listnew.php', ['panelId' => $panelId]);
        ?>
    </div>

    <div class="col-md-6">
        <?php
        echo $this->renderFile('@app/views/site/_listcontinue.php', ['panelId' => $panelId]);
        ?>
    </div>
</div>
<div class="col-lg-12">

    <div class="col-md-6">
        <?php
        echo $this->renderFile('@app/views/site/_listnewmeeting.php', ['panelId' => $panelId]);
        ?>
    </div>

    <div class="col-md-6">
        <?php
        echo $this->renderFile('@app/views/site/_listcontinuemeeting.php', ['panelId' => $panelId]);
        ?>
    </div>
</div>
