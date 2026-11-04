<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionProjectConsultant */
?>
<div class="submission-project-consultant-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_consultant_id',
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
