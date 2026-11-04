<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Title;
use app\models\Organization;
use app\models\Department;
use app\models\Position;
use app\models\JobCategory;
use app\models\Panel;
use app\models\Role;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;

$currentRole = \Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\Person */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="person-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>
    <?php if ($currentRole['role_id'] == \app\models\Role::ADMIN) { ?>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'is_researcher_crec')->checkbox(); ?>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-lg-4">
            <?php
            $data = ArrayHelper::map(Title::find()->isDeleted(FALSE)->orderBy('CONVERT(title.name USING TIS620) ASC')->all(), 'id', 'i18nName');
            echo $form->field($model, 'title_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?> 
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'first_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'last_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'expertise')->textarea(['maxlength' => true]) ?>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php
            // Usage with ActiveForm and model
            $data = ArrayHelper::map(Organization::find()->isDeleted(FALSE)->orderBy('CONVERT(organization.name USING TIS620) ASC')->all(), 'id', 'i18nName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
            echo $form->field($model, 'organization_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>  
        </div>
        <div class="col-lg-6">
            <?php
            $data = [];
            if (!empty($model->organization_id)) {
                $data = ArrayHelper::map(Department::find()->isDeleted(FALSE)->organization($model->organization_id)->orderBy('CONVERT(department.name USING TIS620)')->all(), 'id', 'i18nName');
                // \yii\helpers\VarDumper::dump($data, 10, TRUE);
            }
            echo $form->field($model, 'department_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'select2Options' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'pluginOptions' => [
                    'depends' => [Html::getInputId($model, 'organization_id')],
                    'url' => Url::to(['/department/list']),
                    'placeholder' => '',
                ],
            ]);
            ?> 
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php
            $data = [];
            if (!empty($model->department_id)) {
                $data = ArrayHelper::map(\app\models\Division::find()->isDeleted(FALSE)->department($model->department_id)->orderBy('CONVERT(division.name USING TIS620)')->all(), 'id', 'i18nName');
                // \yii\helpers\VarDumper::dump($data, 10, TRUE);
            }
            echo $form->field($model, 'division_id')->widget(DepDrop::classname(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => [
                    'placeholder' => yii::t('app', 'ภาควิชา'),
                ],
                'select2Options' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'pluginOptions' => [
                    'depends' => [Html::getInputId($model, 'department_id')],
                    'url' => Url::to(['/division/list']),
                    'placeholder' => '',
                ],
            ]);
            ?> 
        </div>
        <div class="col-lg-6">
            <?php
            $data = ArrayHelper::map(Position::find()->isDeleted(FALSE)->orderBy('CONVERT(position.name USING TIS620) ASC')->all(), 'id', 'i18nName');
            echo $form->field($model, 'position_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <?php
            $data = ArrayHelper::map(JobCategory::find()->isDeleted(FALSE)->orderBy('CONVERT(job_category.name USING TIS620) ASC')->all(), 'id', 'name');
            echo $form->field($model, 'job_category_id')->widget(Select2::classname(), [
                'data' => $data,
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'is_paediatrician')->checkbox(); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'is_external')->checkbox(); ?>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-4">
            <?= $form->field($regForm, 'username')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($regForm, 'password')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($regForm, 'password_repeat')->passwordInput(['maxlength' => true]) ?>  
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?=
            $form->field($model, 'cv_file')->label(Yii::t('app', '<font style="color: red">** ในกรณีแก้ไขข้อมูลส่วนตัว/แนบไฟล์ CV ใหม่ หลังจากแนบไฟล์แล้วให้กดปุ่มบันทึกเพื่อทำการบันทึกข้อมูล</font>'))->widget(FileInput::classname(), [
                'options' => [
                    'accept' => '*',
                //'multiple' => 'true',
                ],
                'pluginOptions' => [
                    'theme' => 'gly',
                    'showPreview' => FALSE,
                    'showRemove' => FALSE,
                    'showUpload' => false,
                    'browseLabel' => '',
                    'removeLabel' => '',
                    'initialPreview' => [
                        // $model->cvFileUrl
                        Url::to(['person/download', 'id' => $model->id])
                    ],
//                    'initialPreviewAsData' => TRUE,
                    'initialCaption' => $model->cv_file,
                    'initialPreviewConfig' => [
//                        ['type' => "pdf"]
//                            ['caption' => $model->cv_file]
                    ],
                //'overwriteInitial' => false
                ],
            ]);
            if (isset($model->cv_file)) {
                echo Html::a('<i class="icon md-attachment"></i> ' . Yii::t('app', 'ไฟล์ประวัติ'),
                        // $model->cvFileUrl,
                        Url::to(['person/download', 'id' => $model->id]),
                        ['target' => '_blank']);
                if (!$model->isNewRecord) {
                    echo ' ' . Html::a('<i class="icon md-edit"></i> ' . Yii::t('app', 'ลงนามอิเล็กทรอนิกส์'),
                            ['person/esign', 'personId' => $model->id, 'docType' => \app\models\PersonDocumentAudit::DOC_TYPE_CV],
                            ['role' => 'modal-remote', 'class' => 'btn btn-sm btn-success', 'title' => Yii::t('app', 'ลงนามอิเล็กทรอนิกส์')]);
                    if (!empty($model->cv_signed_at)) {
                        echo ' <span class="label label-info">' . Yii::t('app', 'ลงนามเมื่อ') . ' ' . Yii::$app->formatter->asDatetime($model->cv_signed_at) . '</span>';
                    }
                }
            }
            ?>

        </div>

    </div>
    <div class="row">
        <div class="col-lg-6">
            <?= $form->field($model, 'cv_updated_at')->widget(DateControl::classname(), [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => ['endDate' => '0d'],
                ],
            ])->label(Yii::t('app', 'วันที่ปรับปรุงประวัติ (CV) ล่าสุด')) ?>
        </div>
    </div><br>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'บันทึก'), ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
