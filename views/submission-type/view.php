<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionType */
?>
<div class="submission-type-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'is_new',
            'is_fullboard',
            'is_exemption',
            'agenda_title',
            'submission_type_group_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
