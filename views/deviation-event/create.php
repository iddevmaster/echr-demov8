<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DeviationEvent */
?>
<div class="deviation-event-create">
<?=
$this->render('_form', [
    'model' => $model,
    'devEthicses' => $devEthicses,
])
?>
</div>
