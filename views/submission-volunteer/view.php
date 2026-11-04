<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionVolunteer */
?>
<div class="submission-volunteer-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'volunteer_id',
            'type',
            'follow_up_no',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
