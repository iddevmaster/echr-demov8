<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\EmailQueue */
?>
<div class="email-queue-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'model_id',
            'type',
            'mail_at',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
