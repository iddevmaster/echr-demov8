<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\datecontrol\DateControl;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

$currentRole = \Yii::$app->session->get('currentRole');
$data = ArrayHelper::map(app\models\PersonRole::find()->joinWith(['person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');
?>

<div class="submission-form">
    <?php
    $options = [];
    if (isset($action)) {
        $options['action'] = $action;
    }
    $form = ActiveForm::begin($options
//                'id' => 'form-submission',
//        'action' => \yii\helpers\Url::to(['submission/update', 'id' => $model->id])
    );
    ?>
    <div class="row">
        <?php if ($currentRole['role_id'] == \app\models\Role::RESEARCHER || $currentRole['role_id'] == \app\models\Role::STAFF) { ?>

            <div class="col-md-6">
                <?php
                // Usage with ActiveForm and model
//                $data = ArrayHelper::map(app\models\PersonRole::find()->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
                echo $form->field($project, 'project_coordinator_id')->label(Yii::t('app', 'เลือกผู้ประสานงานโครงการ'))->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person_id != $currentRole['person_id'])],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <?php
                // Usage with ActiveForm and model
//                $data = ArrayHelper::map(app\models\PersonRole::find()->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');
//                $data = ArrayHelper::map(app\models\PersonRole::find()->joinWith(['person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');

//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
                echo $form->field($project, 'project_coordinator_2nd_id')->label(Yii::t('app', 'เลือกผู้ประสานงานโครงการคนที่ 2'))->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person_id != $currentRole['person_id'])],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <?php
                // Usage with ActiveForm and model
//                $data = ArrayHelper::map(app\models\PersonRole::find()->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');
//                $data = ArrayHelper::map(app\models\PersonRole::find()->joinWith(['person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');

//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
                echo $form->field($project, 'project_coordinator_3rd_id')->label(Yii::t('app', 'เลือกผู้ประสานงานโครงการคนที่ 3'))->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person_id != $currentRole['person_id'])],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
            </div>
            <div class="col-md-6">

    <?php
    // Usage with ActiveForm and model
//                $data = ArrayHelper::map(app\models\PersonRole::find()->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');
//    $data = ArrayHelper::map(app\models\PersonRole::find()->joinWith(['person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::COORDINATOR, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'person.user_id', 'person.fullName');

//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
    echo $form->field($project, 'project_viewer_id')->label(Yii::t('app', 'เลือก Monitor (ถ้ามี)'))->widget(Select2::classname(), [
        'data' => $data,
        'options' => ['placeholder' => '', 'disabled' => ($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person_id != $currentRole['person_id'])],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>
            </div>

<?php } else { ?>
            <div class="alert alert-warning alert-dismissible">
            <?php
            $co = '';
            if (isset($model->project_coordinator_id)) {
                $co .= Yii::t('app', 'คนที่ 1 : ') . $model->projectCoordinator->person->i18nFullName;
            } elseif (isset($model->project_coordinator_2nd_id)) {
                $co .= '<br>' . Yii::t('app', 'คนที่ 2 : ') . $model->projectCoordinator2nd->person->i18nFullName;
            } elseif (isset($model->project_coordinator_2nd_id)) {
                $co .= '<br>' . Yii::t('app', 'คนที่ 3 : ') . $model->projectCoordinator3rd->person->i18nFullName;
            }
            ?>
                <?=
                Yii::t('app', 'ผู้ประสานงานโครงการ : ');
                if (isset($model->project_coordinator_id)) {
                    echo $co;
                } else {
                    echo Yii::t('app', 'โครงการนี้ไม่มีผู้ประสานงานโครงการ');
                }
                ?>
            </div>
            <?php } ?>
    </div>
        <?php if (!Yii::$app->request->isAjax) { ?>
        <?php if (($currentRole['role_id'] == \app\models\Role::RESEARCHER && $model->projectLeader->person_id == $currentRole['person_id']) || $currentRole['role_id'] == \app\models\Role::STAFF): ?>
            <div class="form-group">
            <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-save-coordinator']) ?>
            </div>
            <?php endif; ?>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$js = <<<js
    $('.btn-save-coordinator').click(function() {
        var form = $(this).closest('form');
//        alert(form);
        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'POST',
            dataType: 'JSON',
            success: function(res, textStatus, jqXHR) {
            window.location = window.location
//                if (res.forceReload) {
//                    $.pjax.reload(res.forceReload);
//                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
            }
        });
    });     
js;
$this->registerJs($js);
