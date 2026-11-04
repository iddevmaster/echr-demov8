<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionProjectResearcher */
?>
<div class="submission-project-researcher-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_researcher_id',
            'submission_id',
            'status',
            'remark',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
