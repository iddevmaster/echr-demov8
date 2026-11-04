<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingRoom */
?>
<div class="meeting-room-view">
 
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
