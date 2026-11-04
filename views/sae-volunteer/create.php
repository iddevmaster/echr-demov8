<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SaeVolunteer */

?>
<div class="sae-volunteer-create">
    <?= $this->render('_form', [
        'model' => $model,
        'saeEthicses' => $saeEthicses,
    ]) ?>
</div>
