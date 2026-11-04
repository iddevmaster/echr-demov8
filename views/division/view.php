<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Division */
?>
<div class="division-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'department_id',
            'name',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
