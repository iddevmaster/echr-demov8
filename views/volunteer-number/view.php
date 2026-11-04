<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\VolunteerNumber */
?>
<div class="volunteer-number-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',

        ],
    ]) ?>

</div>
