<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PersonRole */
?>
<div class="person-role-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'person_id',
            'role_id',
            'sign',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'effective_date',
            'effective_number',
            'expire_date',
            'status',
        ],
    ]) ?>

</div>
