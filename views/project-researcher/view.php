<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectResearcher */
?>
<div class="project-researcher-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'is_leader',
            'person_id',
            'project_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
