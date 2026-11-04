<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Submission;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\models\Role;
use kartik\datecontrol\DateControl;

$currentRole = \Yii::$app->session->get('currentRole');
?>
<div class="submission-search">
    <div class="panel">
                <div class="panel-heading" >
            <h3 class="panel-title"><?= Yii::t('app', 'ตรวจสอบข้อมูลการรับรองเอกสาร (COA)') ?></h3>
        </div>
        <div class="panel-body">

            <?php
            $form = ActiveForm::begin([
                        'method' => 'get',
                        'action' => Url::to(['site/coa-check']),
                        'options' => [
                            'data-pjax' => 1,
                        ],
//                'type' => ActiveForm::TYPE_INLINE,
            ]);
            ?> 
            <?= $form->field($searchModel, 'coa_token')->label(FALSE)->textInput(['placeholder' => yii::t('app', 'ค้นหาตาม COA Token')]); ?>
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>

            <!-- Wizard Content -->
        </div>
    </div>
</div>