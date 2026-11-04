<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use app\models\SubmissionTypeVolunteerNumber;
use app\models\SubmissionVolunteerNumber;
use yii\helpers\Html;

?>
<div class="row">
    <?php
    
    foreach ($submissionNumbers as $index => $number):
//        yii\helpers\VarDumper::dump($number, 4, TRUE);
//    exit;
        ?>
        <div class="col-md-12">
            <?php echo $form->field($number, "[$index]value")->label($number->volunteerNumber->name); ?>
        </div>
        <?php
    endforeach;
    ?>
    <div class="form-group">
        <div class="pull-left">
            <?= Html::submitButton(Yii::t('app', 'ก่อนหน้า'), ['class' => 'btn btn-primary btn-prev', 'name' => 'previousStep', 'value' => \app\controllers\SubmissionController::CONT_STEP1, 'data-pjax' => 0]) ?>
        </div>
        <div class="pull-right">
            <?= Html::submitButton(Yii::t('app', 'ถัดไป'), ['class' => 'btn btn-primary btn-next', 'name' => 'nextStep', 'value' => \app\controllers\SubmissionController::CONT_STEP3, 'data-pjax' => 0]) ?>
        </div>
    </div>
</div>