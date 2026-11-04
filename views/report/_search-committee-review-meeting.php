<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use app\models\Meeting;
use app\models\MeetingAgenda;
use app\models\Person;
use app\models\Role;
use app\models\SubmissionCommittee;
use kartik\datecontrol\DateControl;
use yii\helpers\Url;
use yii\web\JsExpression;

$searchRole = Role::COMMITTEE;
$currentRole = Yii::$app->session->get('currentRole');
?>
<div class="deviation-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
        'method' => 'get',
        'action' => Url::to(['report/committee-review-meeting']),
        'options' => [
            'data-pjax' => 1,
            //                            'target' => '#crud-datatable-ticket-h-pjax'
            // 'class' => 'form-inline'
        ],
        //                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <?php if ($currentRole['role_id'] != Role::COMMITTEE): ?>
        <div class="col-lg-3">
            <?=
            $form->field($searchModel, 'person_id')->label(Yii::t('app', 'เลือกกรรมการ'))->widget(Select2::classname(), [
                'options' => ['placeholder' => yii::t('app', 'เลือกกรรมการ')],
                'pluginOptions' => [
                    'data' =>[['id' => $searchModel->person_id, 'name' => isset($searchModel->person) ? $searchModel->person->fullName : ""]],
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'ajax' => [
                        'delay' => 250,
                        'url' => Url::to(['person/search']),
                        'dataType' => 'json',
                        'data' => new JsExpression("function(params) { return {q:params.term, roleId: {$searchRole}}; }"),
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function (person) {
                if (person.loading) {
                    return person.text;
                }
                return person.name;
            }'),
                    'templateSelection' => new JsExpression('function (person) {
                if (!person.name) {
                    return person.text;
                }
                return person.name;
            }'),
                ],
                'pluginEvents' => [
                    "change" => "function(e) {
                var data = $(this).select2('data');
                console.log(data);
            }",
                ],
            ])
            ?>
        </div>
        <?php endif; ?>
        <div class="col-md-3">
            <?=
            $form->field($searchModel, 'startMeetingDate')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
            ]);
            ?>
        </div>
        <div class="col-md-3">
            <?=
            $form->field($searchModel, 'endMeetingDate')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                //        'options' => ['placeholder' => ' ถึงวันที่ '],
            ]);
            ?>
        </div>
    </div>

    <div class="form-group margin-0">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>