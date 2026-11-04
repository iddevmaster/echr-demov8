<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PersonRole */
?>
<div class="person-role-create">
<?=
$this->render('_form', [
    'model' => $model,
    'modelPerson' => $modelPerson,
    'personId' => $personId,
    'roleId' => $roleId,
   // 'panelRole' => $panelRole,
])
?>
</div>
