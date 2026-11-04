<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectAgendaQuestion */
?>
<div class="project-agenda-question-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'agenda_id',
            'project_question_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
