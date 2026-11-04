<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Title */
?>
<div class="title-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            

        ],
    ]) ?>

</div>
