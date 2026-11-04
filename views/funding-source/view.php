<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FundingSource */
?>
<div class="funding-source-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',

        ],
    ]) ?>

</div>
