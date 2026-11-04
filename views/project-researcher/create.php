<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ProjectResearcher */

?>
<div class="project-researcher-create">
    <?= $this->render('_form', [
        'model' => $model,
        'submissionId'=>$submission->id
    ]) ?>
</div>
