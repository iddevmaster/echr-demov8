<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>

<div class="submission-update">
    <div class="alert alert-info dark">
        <?= Yii::t('app', 'เปลี่ยนแปลงผู้รับผิดชอบโครงการ') ?>
    </div>
    <?=
    $this->render('_form-change-responsible', [
        'model' => $model
    ])
    ?>

</div>

