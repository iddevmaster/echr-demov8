<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\DetailView;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//if ($step != \app\controllers\SubmissionController::NEW_STEP4) {
//    return '';
//}
$warningMsg = Yii::t('app', 'กรุณาตรวจสอบข้อมูลโครงการก่อนยืนยันการส่งข้อมูล หากท่านยืนยันแล้วท่านจะไม่สามารถแก้ไขข้อมูลโครงการได้อีก');
$confirmMsg = Yii::t('app', 'ต้องการยืนยันการส่งข้อมูลใช่หรือไม่?');
?>
<?php if (!$submission->isAllResearcherAcknowledged ): ?>
        <p class="red-700 font-size-26"><i class="icon md-alert-octagon" aria-hidden="true"></i> <?= Yii::t('app', 'ผู้ร่วมวิจัยยังตอบรับไม่ครบ ยังไม่สามารถยืนยันการส่งโครงการได้') ?></p>

 
<?php endif; ?>
<?php if (!$submission->isAllConsultantAcknowledged ): ?>
                <p class="red-700 font-size-26"><i class="icon md-alert-octagon" aria-hidden="true"></i> <?= Yii::t('app', 'ที่ปรึกษายังตอบรับไม่ครบ ยังไม่สามารถยืนยันการส่งโครงการได้') ?></p>


<?php endif; ?>
<p class="red-700 font-size-26"><i class="icon md-alert-octagon" aria-hidden="true"></i> <?= $warningMsg ?></p>
<div class="row">
    <div class="col-md-12">
        <?= $this->render('_general', [
            'submission' => $submission
        ]) ?>

    </div>

    <div class="col-md-12">

        <?=
        $this->renderFile('@app/views/submission-document/show.php', [
            'submission' => $submission,
            'dataProvider' => $dataProvider,
        ]);
        ?>


    </div>

</div>
<div class="form-group">
    <div class="pull-left">
        <?= Html::submitButton(Yii::t('app', 'ก่อนหน้า'), ['class' => 'btn btn-primary btn-prev', 'name' => 'previousStep', 'value' => $step - 1]) ?>
    </div>
    <div class="pull-right">
        <?php if ($submission->isAllResearcherAcknowledged && $submission->isAllConsultantAcknowledged): ?>
            <?= Html::submitButton(Yii::t('app', 'ยืนยัน'), ['class' => 'btn btn-primary btn-next btn-confirm', 'name' => 'nextStep', 'value' => $step + 1]) ?>
        <?php endif; ?>
    </div>
</div>

<?php
$msg = Yii::t('app', 'กำลังส่งข้อมูล...');
$js = <<<js
    $('.btn-confirm').click(function(ev) {
        
        dlgPrimary.confirm('<div class="alert alert-danger">{$warningMsg}</div>{$confirmMsg}', function(out){
            if(out) {
                $(this).prop('disabled', true);
                $('#form-submission').submit();
        //        dlgInfo.alert('{$msg}');
                $.blockUI({ 
                    message: '<h1>{$msg}</h1>',
                    css: {
                        border: 'none',
                    },
                    
                }); 
            }
        });
        ev.preventDefault();
//        $(this).prop('disabled', true);
//        $('#form-submission').submit();
////        dlgInfo.alert('{$msg}');
//        $.blockUI({ 
//            message: '<h1>{$msg}</h1>',
//            css: {
//                border: 'none',
//            },
//            
//        }); 
    });
js;
$this->registerJs($js);
