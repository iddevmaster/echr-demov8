<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use app\models\Person;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingPerson */
/* @var $form yii\widgets\ActiveForm */
$elRoleName = Html::getInputId($model, 'role_name');
?>

<div class="meeting-person-form">

<?php $form = ActiveForm::begin(); ?>


    <?php
    $roleNameUrl = Url::to(['person/role-name-by-panel', 'panelId' => $model->meeting->panel_id]);
    $data = ArrayHelper::map(Person::find()->joinWith(['personRoles', 'personRoles.personRolePanels'])
                            ->isDeleted(FALSE)->panel($model->meeting->panel_id)->notInMeeting($model->meeting_id)->all(), 'id', 'fullNameWithEng');
    echo $form->field($model, 'person_id')->widget(Select2::className(), [
        'data' => $data,
        'options' => ['placeholder' => ''],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            'change' => "function() { 
                var data = $(this).select2('data');
                if (data.length > 0 && data[0].id) {
                    $.ajax({
                        url: '{$roleNameUrl}',
                        data: {id: data[0].id},
                        method: 'GET',
                        dataType: 'JSON',
                        success: function(res, textStatus, jqXHR) {
                            $('#{$elRoleName}').val(res.role_name);

                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
                        }
                    });
                }

            }",
        ],
    ])
    ?>

<?= $form->field($model, 'role_name')->textInput(['readonly' => TRUE]) ?>


        <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

<?php ActiveForm::end(); ?>

</div>
