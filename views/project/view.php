<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Project */
?>
<div class="project-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_thai',
            'name_eng',
            'start_date',
            'end_date',
            'funding_source_id',
            'funding_source_description',
            'is_child_project',
            'progress_period',
            'remark',
            'certified_date',
            'status',
            'project_code',
            'panel_id',
            'organization_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
