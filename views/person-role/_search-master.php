<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;

$elOrg = Html::getInputId($searchModel, 'personOrg');
$elDep = Html::getInputId($searchModel, 'personDepartment');
$elDivision = Html::getInputId($searchModel, 'personDivision');
?>
<div class="document-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => Url::to(['person-role/select-person', 'id' => $searchModel->role_id]),
                'options' => [
                    'data-pjax' => 1,
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <div class="col-md-4"> <?= $form->field($searchModel, 'name')->label(FALSE)->textInput(['placeholder' => 'ชื่อ']); ?></div>

            <div class="form-group text-left col-md-2">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>
   </div>




    <?php ActiveForm::end(); ?>
</div>
