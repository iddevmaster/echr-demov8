<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Agenda */
?>
<div class="agenda-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'submission_type_id',
            'label',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
