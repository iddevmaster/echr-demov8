<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PersonRole */
?>
<div class="person-role-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelPerson'=>$modelPerson
    ]) ?>

</div>
