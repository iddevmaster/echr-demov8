<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DeviationAssessForm */
?>
<div class="deviation-assess-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'submission_committee_id',
            'review_choice_id',
            'review_choice_text',
            'resolution_id',
            'suggestion:ntext',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
