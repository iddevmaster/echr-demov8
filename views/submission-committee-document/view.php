<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionCommitteeDocument */
?>
<div class="submission-committee-document-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'file_name',
            'remark',
            'submission_committee_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
