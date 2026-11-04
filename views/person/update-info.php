<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
?>
<div class="person-update">
<?=
$this->render('_form', [
    'model' => $model,
    'regForm' => $regForm,

])
?>
</div>
