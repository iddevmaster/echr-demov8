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
                'action' => Url::to(['submission/project-submission', 'submissionId' => $submissionId]),
                'options' => [
                    'data-pjax' => 1,
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <div class="col-md-6"><?= $form->field($searchModel, 'expertise')->label(FALSE)->textInput(['placeholder' => 'ความชำนาญ']); ?></div>
        <div class="col-md-6"> <?= $form->field($searchModel, 'name')->label(FALSE)->textInput(['placeholder' => 'ชื่อ']); ?></div>
        <div class="col-md-4"> <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(app\models\Organization::find()->isDeleted(FALSE)->orderBy('CONVERT(organization.name USING TIS620) ASC')->all(), 'id', 'i18nName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($searchModel, 'personOrg')->label(FALSE)->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'เลือกหน่วยงาน')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>  </div>
        <div class="col-md-4"><?php
            $data = ArrayHelper::map(app\models\Department::find()->isDeleted(FALSE)->orderBy('CONVERT(department.name USING TIS620)')->all(), 'id', 'i18nName');
            echo $form->field($searchModel, 'personDepartment')->label(FALSE)->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'เลือกคณะ')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
//            $data = [];
//            if (!empty($searchModel->personOrg)) {
//                $data = ArrayHelper::map(app\models\Department::find()->isDeleted(FALSE)->organization($searchModel->personOrg)->orderBy('CONVERT(department.name USING TIS620)')->all(), 'id', 'name');
//                // \yii\helpers\VarDumper::dump($data, 10, TRUE);
//            }
//            echo $form->field($searchModel, 'personDepartment')->label(FALSE)->widget(DepDrop::classname(), [
//                'type' => DepDrop::TYPE_SELECT2,
//                'data' => $data,
//                'select2Options' => [
//                    'pluginOptions' => ['allowClear' => true]
//                ],
//                'pluginOptions' => [
//                    'depends' => [Html::getInputId($searchModel, 'personOrg')],
//                    'url' => Url::to(['/department/list']),
//                    'placeholder' => '',
//                ],
////                'pluginEvents' => [
////                    "depdrop:afterChange" => "function(event, id, value) {
////                    if (_person) {
////                        $('#{$elDep}').val(_person.department_id).trigger('depdrop:change');
////                    }
////                }",
////                ]
//            ]);
            ?> 
        </div>
                <div class="col-md-4"> <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(app\models\Division::find()->isDeleted(FALSE)->orderBy('CONVERT(division.name USING TIS620) ASC')->all(), 'id', 'i18nName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($searchModel, 'personDivision')->label(FALSE)->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'เลือกภาควิชา')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>  </div>
    </div>

    <div class="form-group text-right">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>


    <?php ActiveForm::end(); ?>
</div>
