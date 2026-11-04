<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireTitle */
?>
<div class="questionnaire-title-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_type_id',
            'questionnaire_type',
            'title',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
