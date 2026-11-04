<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PersonSubmissionType */
?>
<div class="person-submission-type-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'person_id',
            'submission_type_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
