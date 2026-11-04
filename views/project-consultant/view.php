<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectConsultant */
?>
<div class="project-consultant-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'person_id',
            'project_id',
            'submission_id',
            'mail_sent',
            'mail_sent_at',
            'acknowledge_status',
            'acknowledge_by',
            'acknowledge_at',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
