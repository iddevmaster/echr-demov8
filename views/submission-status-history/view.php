<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionStatusHistory */
?>
<div class="submission-status-history-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'status',
            'created_by',
            'created_at',
        ],
    ]) ?>

</div>
