<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Panel */
?>
<div class="panel-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',

        ],
    ]) ?>

</div>
