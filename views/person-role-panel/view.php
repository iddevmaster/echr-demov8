<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PersonRolePanel */
?>
<div class="person-role-panel-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'person_role_id',
            'panel_id',
            'deleted',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
            'is_regular',
        ],
    ]) ?>

</div>
