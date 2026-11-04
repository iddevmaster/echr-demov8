<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use kartik\depdrop\DepDrop;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\Province;
use app\models\Amphur;
use app\models\BusinessType;

/* @var $this yii\web\View */
/* @var $personal app\models\RegisterTransaction */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col-md-4">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('app', 'ข้อมูลผู้ใช้'); ?></h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td class="font-weight-900"><?= Yii::t('app', 'ชื่อผู้ใช้'); ?></td>
                            <td><?= $regForm->username ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"><?= Yii::t('app', 'รหัสผ่าน'); ?></td>
                            <td><?= $regForm->password ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    <div class="col-md-8">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('app', 'ข้อมูลนักวิจัย'); ?></h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        
                       <tr>
                            <td class="font-weight-900"><?= Yii::t('app', 'ชื่อ-สกุล'); ?></td>
                            <td colspan="3"><?= "{$profile->fullNameWithEng}" ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"><?= Yii::t('app', 'เบอร์โทร'); ?></td>
                            <td><?= $profile->tel ?></td>
                            <td class="font-weight-900"><?= Yii::t('app', 'อีเมลล์'); ?></td>
                            <td><?= $profile->email ?></td>
                        </tr>
                        <tr>
                            <td class="font-weight-900"><?= Yii::t('app', 'หน่วยงาน'); ?></td>
                            <td colspan="3"><?= $profile->fullOrg ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row">
    
    <div class="form-group">
    <div class="pull-left">
        <?= Html::submitButton(Yii::t('app', '<span><i class="icon wb-arrow-left" aria-hidden="true"></i> ก่อนหน้า </span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary btn-prev', 'name' => 'previous-step']) ?>
    </div>
    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', '<span>ยืนยัน <i class="icon wb-check" aria-hidden="true"></i></span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary btn-confirm', 'name' => 'next-step']) ?>
    </div>
</div>
</div>
<?php
$msg = Yii::t('app', 'กำลังส่งข้อมูล...');
$js = <<<js
    $('.btn-confirm').click(function() {
        $(this).prop('disabled', true);
        $('#form-register').submit();
//        dlgInfo.alert('{$msg}');
        $.blockUI({ 
            message: '<h1>{$msg}</h1>',
            css: {
                border: 'none',
            },
            
        }); 
    });
js;
$this->registerJs($js);

      