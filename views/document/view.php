<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Document */
?>
<div class="document-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'number',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'template_file',
            'role_id',
        ],
    ]) ?>

</div>
