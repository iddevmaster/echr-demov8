<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
?>
<div class="person-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'idcard_no',
            'title_id',
            'first_name',
            'last_name',
            'role_id',
            'user_id',
            'email:email',
            'tel',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
