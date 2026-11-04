<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrainingType */
?>
<div class="training-type-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'validity_years',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
