<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireChoice */
?>
<div class="questionnaire-choice-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'questionnaireTitle.title',
            'title',

        ],
    ]) ?>

</div>

