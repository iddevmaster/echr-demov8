<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>
<div class="submission-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'remark',
            'certified_date',
            'status',
            'project_id',
            'organization_id',
            'full_doc_file',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'submission_type_id',
        ],
    ]) ?>

</div>
