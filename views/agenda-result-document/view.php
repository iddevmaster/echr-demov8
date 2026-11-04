<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AgendaResultDocument */
?>
<div class="agenda-result-document-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'agenda_id',
            'result_document_id',
            'remark',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
