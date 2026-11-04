<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
$this->title = Yii::t('app', 'Report Portal');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="body-content">
    <div class="" data-plugin="animateList" data-delay="200">
        <?php foreach ($menus as $menu): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?= $menu['label'] ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php foreach ($menu['items'] as $item): ?>

                                    <div class="col-sm-6">
                                        <?=
                                        Html::a('<i class="site-menu-icon icon md-search-in-file"></i> '.$item['label'], $item['url'], [
                                            'class' => 'btn btn-primary btn-raised btn-block margin-bottom-10 btn-lg text-left'
                                        ]);
                                        ?>
                                    </div>
    <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php endforeach; ?>
    </div>
        <div class="" data-plugin="animateList" data-delay="200">
        <?php foreach ($gds as $gd): ?>
            <div class="row">
                <div class="col-md-12 ">
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?= $gd['label'] ?></h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <?php foreach ($gd['items'] as $item): ?>

                                    <div class="col-sm-12">
                                        <?=
                                        Html::a('<i class="site-menu-icon icon fa-line-chart"></i> '.$item['label'], $item['url'], [
                                            'class' => 'btn btn-default btn-raised btn-block margin-bottom-10 btn-lg text-left'
                                        ]);
                                        ?>
                                    </div>
    <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php endforeach; ?>
    </div>
</div>