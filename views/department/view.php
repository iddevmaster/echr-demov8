<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Department */
?>
<div class="department-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'organization.name',
            'name',
            'name_eng',
            'address',
            'tel',
            'email:email',
            'website',
        ],
    ])
    ?>

</div>
