<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JobCategory */
?>
<div class="job-category-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
