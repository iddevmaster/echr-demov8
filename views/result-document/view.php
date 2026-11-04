<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ResultDocument */
?>
<div class="result-document-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'resolution',
            'committee_resolution',
            'template_file',
            'remark',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
