<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Volunteer */
?>
<div class="volunteer-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_id',
            'code',
            'status',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
