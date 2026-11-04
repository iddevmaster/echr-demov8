<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionCoiPerson */
?>
<div class="submission-coi-person-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'person_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
