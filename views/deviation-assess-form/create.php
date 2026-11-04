<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\DeviationAssessForm */

?>
<div class="deviation-assess-form-create">
    <?= $this->render('_form', [
        'model' => $model,
        'reviewChoices' => $reviewChoices,
        'resolutions' => $resolutions,
    ]) ?>
</div>
