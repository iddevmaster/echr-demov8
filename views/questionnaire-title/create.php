<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireTitle */

?>
<div class="questionnaire-title-create">
    <?= $this->render('_form', [
        'model' => $model,
        'submissionTypeId'=>$submissionTypeId
    ]) ?>
</div>
