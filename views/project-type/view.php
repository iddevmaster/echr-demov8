<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectType */
?>
<div class="project-type-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'is_alert',
            'min_occur',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
