<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
?>

<div class="submission-update">
    <div class="alert alert-info dark col-md-6">
        <?= Yii::t('app', 'ผู้ประสานงานโครงการวิจัย คือ ผู้ที่ดำเนินการส่งเอกสารแทนหัวหน้าโครงการวิจัย') ?>
    </div>
    <div class="alert alert-warning dark col-md-6">
        <?= Yii::t('app', 'Monitor คิอ ผู้ที่สามารถตรวจสอบการดำเนินการโครงการ เช่น CRA ตัวแทนบริษัท ตัวแทนผู้ให้ทุน') ?>
    </div>
    <?=
    $this->render('_form-coordinator', [
        'action' => $action,
        'model' => $model,
        'project' => $project,
    ])
    ?>

</div>

