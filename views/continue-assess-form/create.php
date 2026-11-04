<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContinueAssessForm */
?>
<div class="continue-assess-form-create">
    <?=
    $this->render('_form', [
        'model' => $model,
        'ethicses' => $ethicses,
        'conEthicses' => $conEthicses,
        'reviewChoices' => $reviewChoices,
        'resolutions' => $resolutions,
    ])
    ?>
</div>
