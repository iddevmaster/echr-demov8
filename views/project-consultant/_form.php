<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\models\Role;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectResearcher */
/* @var $form yii\widgets\ActiveForm */
$researcherRole = Role::RESEARCHER;
$data = isset($model->person_id) ? [['id' => $model->person_id, 'name' => "{$model->person->fullName} ({$model->person->fullNameEng})"]] : NULL;
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="project-consultant-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-project-consultant'
    ]); ?>
    <?= $form->errorSummary($model); ?>
    <?=
    $form->field($model, 'person_id')->label('นักวิจัย')->widget(Select2::classname(), [
        'pluginOptions' => [
//            'data' =>[['id' => $model->person_id, 'name' => isset($model->person) ? $model->person->fullName : ""]],
            'data' => $data,
            'allowClear' => true,
            'minimumInputLength' => 2,
            'ajax' => [
                'delay' => 250,
                'url' => Url::to(['person/search-consultant']),
                'dataType' => 'json',
                'data' => new JsExpression("function(params) { return {q:params.term, submissionId: {$model->submission_id}, roleId: {$researcherRole}}; }"),
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
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
