<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Organization */
?>
<div class="organization-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',

        ],
    ]) ?>

</div>
