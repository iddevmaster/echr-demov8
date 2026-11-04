<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */

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

                                    <div class="col-sm-4">
                                        <?=
                                        Html::a($item['label'], $item['url'], [
                                            'class' => 'btn btn-primary btn-raised btn-block margin-bottom-10 btn-lg'
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