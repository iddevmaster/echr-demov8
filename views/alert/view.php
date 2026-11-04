<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Alert */
?>
<div class="alert-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'message',
            'type',
            'is_acknowledged',
            'acknowledged_by',
            'acknowledged_at',
            'alerted_at',
            'created_at',
            'user_id',
            'submission_id',
        ],
    ]) ?>

</div>
