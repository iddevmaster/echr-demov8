<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DeviationEvent */
?>
<div class="deviation-event-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'submission_committee_id',
            'submission_event_id',
            'is_major_minor_com',
            'comment:ntext',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
