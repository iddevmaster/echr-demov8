<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SaeVolunteer */
?>
<div class="sae-volunteer-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_id',
            'submission_committee_id',
            'volunteer_id',
            'dead',
            'cured',
            'drug',
            'comment:ntext',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
