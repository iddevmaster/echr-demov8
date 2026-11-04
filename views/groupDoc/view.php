<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\GroupDoc */
?>
<div class="group-doc-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'name_eng',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
