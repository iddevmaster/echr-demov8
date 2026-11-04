<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */

?>
<div class="sae-assess-form-create">

    <?= $this->render('_form-resolution', [
        'model' => $model,
        'resolutions' => $resolutions,
    ]) ?>
</div>
