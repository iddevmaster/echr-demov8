<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DocumentSubmisstionType */
?>
<div class="document-submission-type-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'submission_type_id',
            'document_id',
            'is_require',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>

</div>
