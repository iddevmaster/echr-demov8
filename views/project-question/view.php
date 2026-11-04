<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectQuestion */
?>
<div class="project-question-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'answer_type',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
