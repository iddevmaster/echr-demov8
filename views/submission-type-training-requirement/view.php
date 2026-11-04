<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubmissionTypeTrainingRequirement */
?>
<div class="submission-type-training-requirement-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'submission_type_id',
                'value' => isset($model->submissionType) ? $model->submissionType->name : '',
            ],
            [
                'attribute' => 'category',
                'value' => $model->categoryLabel,
            ],
            [
                'attribute' => 'rule',
                'value' => $model->ruleLabel,
            ],
        ],
    ]) ?>

</div>
