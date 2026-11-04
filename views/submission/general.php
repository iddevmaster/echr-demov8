<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>

<div class="submission-update">
        <div class="alert alert-info dark">
        <?= Yii::t('app', 'ข้อมูลทั่วไปของงานวิจัย') ?>
    </div>
    <?=
    $this->render('_form-general', [
        'model' => $model,
        'project' => $project
    ])
    ?>

</div>

