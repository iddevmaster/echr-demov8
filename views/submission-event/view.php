<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionEvent */
?>
<div class="submission-event-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'event_no',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
