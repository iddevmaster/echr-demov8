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
use yii\helpers\Url;
use yii\web\JsExpression;

$researcherRole = Role::RESEARCHER;
$data = null;
if (!empty($searchModel->is_leader)) {
    $person = Person::findOne($searchModel->is_leader);
    $data = [['id' => $searchModel->is_leader, 'name' => "{$person->fullName} ({$person->fullNameEng})"]];
}
?>
<div class="deviation-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                //                            'target' => '#crud-datatable-ticket-h-pjax'
                // 'class' => 'form-inline'
                ],
                    //                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <div class="col-lg-3">
            <?= $form->field($searchModel, 'name')->label(yii::t('app', 'ค้นหาตามหมายเลขโครงการ'))->textInput(['placeholder' => yii::t('app', 'ค้นหาตามหมายเลขโครงการ')]); ?>
        </div>
        <div class="col-lg-3">
            <?=
            $form->field($searchModel, 'is_leader')->label(Yii::t('app', 'หัวหน้าโครงการ'))->widget(Select2::classname(), [
                'options' => ['placeholder' => yii::t('app', 'เลือกหัวหน้าโครงการ')],
                'pluginOptions' => [
                    //            'data' =>[['id' => $model->person_id, 'name' => isset($model->person) ? $model->person->fullName : ""]],
                    'data' => $data,
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'ajax' => [
                        'delay' => 250,
                        'url' => Url::to(['person/search']),
                        'dataType' => 'json',
                        'data' => new JsExpression("function(params) { return {q:params.term, roleId: {$researcherRole}}; }"),
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
        <div class="col-lg-3">
<?php
$dataCom = app\models\Project::isOngoing();
echo $form->field($searchModel, 'isOngoing')->label('สถานะโครงการ')->widget(Select2::classname(), [
    'data' => $dataCom,
    'options' => ['placeholder' => yii::t('app', 'เลือกสถานะ')],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
?>  
        </div>
    </div>

    <div class="form-group margin-0">
<?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>