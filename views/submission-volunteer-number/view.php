<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionVolunteerNumber */
?>
<div class="submission-volunteer-number-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'value',
            'volunteer_number_id',
            'submission_id',
            'project_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
