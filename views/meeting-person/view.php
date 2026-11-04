<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingPerson */
?>
<div class="meeting-person-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'meeting_id',
            'person_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'role_id',
        ],
    ]) ?>

</div>
