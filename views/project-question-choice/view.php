<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectQuestionChoice */
?>
<div class="project-question-choice-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_question_id',
            'project_type_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
