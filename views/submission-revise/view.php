<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionRevise */
?>
<div class="submission-revise-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'remark',
            'submission_id',
            'send_date',
            'return_date',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
