<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireAnswer */
?>
<div class="questionnaire-answer-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_committee_id',
            'submission_id',
            'questionnaire_title_id',
            'questionnaire_choice_id',
            'text_answer:ntext',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
