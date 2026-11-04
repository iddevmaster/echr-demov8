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
use app\models\Organization;
use app\models\Department;
use app\models\Division;
use app\models\Panel;

$currentRole = \Yii::$app->session->get('currentRole');
$elOrg = Html::getInputId($searchModel, 'researcherOrg');
$elDep = Html::getInputId($searchModel, 'researcherDep');
$elDiv = Html::getInputId($searchModel, 'researcherDiv');
?>
<div class="submission-search">
    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
        'action' => Url::to(['site/report-sum-new', 'searchModel' => $searchModel]),
        'options' => [
            'data-pjax' => 1,
        ],
        //                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>


    <div class="row">
        <div class="col-lg-4">
            <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(Organization::find()->isDeleted(FALSE)->orderBy('CONVERT(organization.name USING TIS620) ASC')->all(), 'id', 'i18nName');
            //    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($searchModel, 'researcherOrg')->label(false)->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'เลือกหน่วยงาน')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>

        </div>
        <div class="col-lg-4">
            <?php
            $data = [];
            if (!empty($searchModel->researcherOrg)) {
                $data = ArrayHelper::map(Department::find()->isDeleted(FALSE)->organization($searchModel->researcherOrg)->orderBy('CONVERT(department.name USING TIS620)')->all(), 'id', 'name');
                // \yii\helpers\VarDumper::dump($data, 10, TRUE);
            }
            echo $form->field($searchModel, 'researcherDep')->label(false)->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'แผนก/คณะ')],
                'select2Options' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'pluginOptions' => [
                    'depends' => [Html::getInputId($searchModel, 'researcherOrg')],
                    'url' => Url::to(['/department/list']),
                    'placeholder' => Yii::t('app', 'แผนก/คณะ'),
                ],
                //            'pluginEvents' => [
                //                "depdrop:afterChange" => "function(event, id, value) {
                //                    if (_person) {
                //                        $('#{$elDep}').val(_person.researcherDep).trigger('depdrop:change');
                //                    }
                //                }",
                //            ]
            ]);
            ?>
        </div>
        <div class="col-lg-4">

            <?php
            // Usage with ActiveForm and model
            $data = [];
            if (!empty($searchModel->researcherDep)) {
                $data = ArrayHelper::map(Division::find()->isDeleted(FALSE)->department($searchModel->researcherDep)->orderBy('CONVERT(division.name USING TIS620)')->all(), 'id', 'name');
                // \yii\helpers\VarDumper::dump($data, 10, TRUE);
            }
            echo $form->field($searchModel, 'researcherDiv')->label(false)->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'ภาควิชา/สาขา')],
                'select2Options' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'pluginOptions' => [
                    'depends' => [Html::getInputId($searchModel, 'researcherDep')],
                    'url' => Url::to(['/division/list']),
                    'placeholder' => Yii::t('app', 'ภาควิชา/สาขา'),
                ],
                //            'pluginEvents' => [
                //                "depdrop:afterChange" => "function(event, id, value) {
                //                    if (_person) {
                //                        $('#{$elDiv}').val(_person.researcherDiv).trigger('change');
                //                    }
                //                }",
                //            ]
            ]);
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <?=
            $form->field($searchModel, 'startDate')->label(false)->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'options' => [
                        'placeholder' => $searchModel->getAttributeLabel('startDate'),
                    ]
                ],
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?=
            $form->field($searchModel, 'endDate')->label(false)->widget(DateControl::className(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'options' => [
                        'placeholder' => $searchModel->getAttributeLabel('endDate'),
                    ]
                ],
            ])
            ?>
        </div>
        <div class="col-md-4">
            <?php
            $data = ArrayHelper::map(Panel::find()->isDeleted(FALSE)->orderBy('CONVERT(panel.name USING TIS620) ASC')->all(), 'id', 'i18nName');
            //    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($searchModel, 'panel_id')->label(false)->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => Yii::t('app', 'เลือก Panel')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <div class="form-group text-left text-bottom">
                <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>