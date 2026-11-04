<?php

use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\Department;
use app\models\JobCategory;
use app\models\Position;
use app\models\Title;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\models\Division;
use kartik\widgets\FileInput;
use kartik\datecontrol\DateControl;
use yii\captcha\Captcha;

/* @var $this yii\web\View */
/* @var $personal app\models\RegisterTransaction */
/* @var $form yii\widgets\ActiveForm */
$elTitleEng = Html::getInputId($model, 'titleEng');
$titleInfoUrl = Url::to(['title/info']);
$checkIdcardUrl = Url::to(['person/check-idcard']);
$elTitle = Html::getInputId($model, 'title_id');
$elFirstName = Html::getInputId($model, 'first_name');
$elLastName = Html::getInputId($model, 'last_name');
$elFirstNameEng = Html::getInputId($model, 'first_name_eng');
$elLastNameEng = Html::getInputId($model, 'last_name_eng');
$elEmail = Html::getInputId($model, 'email');
$elTel = Html::getInputId($model, 'tel');
$elMobile = Html::getInputId($model, 'mobile_no');
$elOrg = Html::getInputId($model, 'organization_id');
$elDep = Html::getInputId($model, 'department_id');
$elDiv = Html::getInputId($model, 'division_id');
$elCvFile = Html::getInputId($model, 'cv_file');
$elAcceptPolicy = Html::getInputId($model, 'accept_policy');

$lang = \Yii::$app->language;
?>

<?php
//\yii\helpers\VarDumper::dump($lang, 10, TRUE);
if ($lang == 'en'):
    ?>
    <div class="row">
        <div class="col-md-2">
            <?=
            $form->field($model, 'title_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Title::find()->isDeleted(FALSE)->orderBy('CONVERT(title.name_eng USING TIS620)')->all(), 'id', 'name_eng'),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
//            'pluginEvents' => [
//                "change" => "function() { 
//                    var data = $(this).select2('data');
//                    if (data.length > 0 && data[0].id) {
//                        $.ajax({
//                            url: '{$titleInfoUrl}',
//                            data: {id: data[0].id},
//                            method: 'GET',
//                            dataType: 'JSON',
//                            success: function(res, textStatus, jqXHR) {
//                                $('#{$elTitleEng}').val(res.name_eng);
//
//                            },
//                            error: function(jqXHR, textStatus, errorThrown) {
//                                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
//                            }
//                        });
//                    }
//                    
//                }",
//            ],
            ]);
            ?>
        </div>

        <div class="col-md-5">
            <?= $form->field($model, 'first_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'last_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-2">
            <?=
            $form->field($model, 'title_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Title::find()->isDeleted(FALSE)->orderBy('CONVERT(title.name USING TIS620)')->all(), 'id', 'name'),
                'options' => ['placeholder' => Yii::t('app', 'เลือกคำนำหน้าชื่อ')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'pluginEvents' => [
                    "change" => "function() { 
                    var data = $(this).select2('data');
                    if (data.length > 0 && data[0].id) {
                        $.ajax({
                            url: '{$titleInfoUrl}',
                            data: {id: data[0].id},
                            method: 'GET',
                            dataType: 'JSON',
                            success: function(res, textStatus, jqXHR) {
                                $('#{$elTitleEng}').val(res.name_eng);

                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
                            }
                        });
                    }
                    
                }",
                ],
            ]);
            ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'titleEng')->textInput(['readonly' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'first_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'last_name_eng')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
<?php endif; ?>
<div class="row">

    <div class="col-md-4">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'mobile_no')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(Organization::find()->isDeleted(FALSE)->orderBy('CONVERT(organization.name USING TIS620) ASC')->all(), 'id', 'i18nName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'organization_id')->label('องค์กร/หน่วยงาน (หากไม่พบ กรุณาติดต่อศูนย์จริยธรรมฯ)')->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => Yii::t('app', 'เลือกหน่วยงาน')],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>      </div>    
    <div class="col-md-6">
        <?php
        $data = [];
        if (!empty($model->organization_id)) {
            $data = ArrayHelper::map(Department::find()->isDeleted(FALSE)->organization($model->organization_id)->orderBy('CONVERT(department.name USING TIS620)')->all(), 'id', 'name');
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
            'pluginEvents' => [
                "depdrop:afterChange" => "function(event, id, value) {
                    if (_person) {
                        $('#{$elDep}').val(_person.department_id).trigger('depdrop:change');
                    }
                }",
            ]
        ]);
        ?>     
    </div>    
</div>
<div class="row">

    <div class="col-md-6">
        <?php
        // Usage with ActiveForm and model
        $data = [];
        if (!empty($model->department_id)) {
            $data = ArrayHelper::map(Division::find()->isDeleted(FALSE)->department($model->department_id)->orderBy('CONVERT(division.name USING TIS620)')->all(), 'id', 'name');
            // \yii\helpers\VarDumper::dump($data, 10, TRUE);
        }
        echo $form->field($model, 'division_id')->widget(DepDrop::classname(), [
            'type' => DepDrop::TYPE_SELECT2,
            'data' => $data,
            'select2Options' => [
                'pluginOptions' => ['allowClear' => true]
            ],
            'pluginOptions' => [
                'depends' => [Html::getInputId($model, 'department_id')],
                'url' => Url::to(['/division/list']),
                'placeholder' => '',
            ],
            'pluginEvents' => [
                "depdrop:afterChange" => "function(event, id, value) {
                    if (_person) {
                        $('#{$elDiv}').val(_person.division_id).trigger('change');
                    }
                }",
            ]
        ]);
        ?>     
    </div>
    <div class="col-md-6">
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(JobCategory::find()->isDeleted(FALSE)->orderBy('job_category.id ASC')->all(), 'id', 'name');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'job_category_id')->widget(Select2::classname(), [
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
    <div class="col-md-4">
        <?= $form->field($model, 'is_paediatrician')->checkbox(); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'is_external')->checkbox(); ?>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <?= Yii::t('app', '<div class="red-600">สิทธิในการเข้าถึงระบบ สำหรับนักศึกษา ป.ตรี ที่จะยื่นโครงการ ให้กดเลือกทั้ง 2 สถานะ และยื่นโครงการโดยเป็นผู้ประสานงาน เลือก อ.ที่ปรึกษาเป็นหัวหน้าโครงการ (นักศึกษา ป.ตรี ยังไม่สามารถเป็นหัวหน้าเองได้)</div>') ?>
        <?= $form->field($model, 'role_ids')->label(false)->checkboxList(\app\models\Role::getRegisterRoles()); ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= Yii::t('app', '<div class="red-600">หมายเหตุ : ต้องลงนามและระบุวันที่ในเอกสาร หากไม่ระบุจะถือว่าเอกสารไม่สมบูรณ์!!</div>') ?>
        <?php
        echo FileInput::widget([
            'id' => 'cv_file',
            'name' => 'cv_file',
            'options' => [
                'multiple' => FALSE,
            ],
            'pluginOptions' => [
                'theme' => 'gly',
                'required' => TRUE,
                'showPreview' => FALSE,
                'showUpload' => FALSE,
                'showRemove' => FALSE,
                'uploadUrl' => urldecode(Url::to(['person/upload-cv'])),
                'uploadExtraData' => [
                    'album_id' => 20,
                    'cat_id' => 'Nature'
                ],
                'msgPlaceholder' => Yii::t('app', 'เลือกไฟล์ประวัติผู้วิจัยเพื่ออับโหลด')
            ],
            'pluginEvents' => [
                "change" => "function() {
//                    $('#{$elCvFile}').val('');
                    setTimeout(() => {
                        $('#cv_file').fileinput('upload');
                    }, 200);
                }",
                'fileuploaded' => "function(event, data, previewId, index) {
                    console.log(data);
                    if (data.response.filename) {
                        $('#{$elCvFile}').val(data.response.filename);
                    }
                }
                "
            ]
        ]);
        echo $form->field($model, 'cv_file')->label(FALSE)->hiddenInput();

        echo $form->field($model, 'cv_updated_at')->widget(DateControl::classname(), [
            'type' => DateControl::FORMAT_DATE,
            'widgetOptions' => [
                'pluginOptions' => ['endDate' => '0d'],
            ],
        ])->label(Yii::t('app', 'วันที่ปรับปรุงประวัติ (CV) ล่าสุด'));

        echo $form->field($model, 'cv_apply_stamp')->label(false)->checkboxList([
            1 => Yii::t('app', 'ต้องการให้ลงนามอิเล็กทรอนิกส์ (ประทับชื่อและวันที่ลงบนเอกสาร)'),
        ]);

        echo $form->field($model, 'verifyCode', [
            'enableClientValidation' => TRUE,
            'enableAjaxValidation' => FALSE,
        ])->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ]);
        ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'accept_cv_certify')->label(false)->checkboxList([1 => Yii::t('app', 'ข้าพเจ้าขอรับรองว่าเอกสารที่อัปโหลด (CV/เอกสารการอบรม) เป็นความจริงและถูกต้องทุกประการ')]); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="checkbox-inline checkbox-primary pull-left">
                <?= $form->field($model, 'accept_policy')->label(false)->checkboxList([1 => Yii::t('app', 'ข้าพเจ้าเข้าใจและตกลงตาม')]); ?>
            </div>
            <p class="margin-left-40"><?= Yii::t('app', 'เงื่อนไขการให้บริการและนโยบายความเป็นส่วนตัว '); ?>  <?= Html::a(Yii::t('app', '[นโยบาย]'), ['person/policy'], ['role' => 'modal-remote', 'data-toggle' => 'tooltip']); ?>.</p>
        </div>
    </div>
</div>

<div class="form-group">

    <div class="pull-right">
        <?= Html::submitButton(Yii::t('app', '<span>ถัดไป <i class="icon wb-arrow-right" aria-hidden="true"></i></span>'), ['class' => 'btn btn-animate btn-animate-side btn-primary', 'name' => 'nextStep', 'value' => app\controllers\PersonController::REGISTER_STEP2]) ?>

    </div>
</div>
<script type="text/javascript">
    var _person = null;
    function checkIdcard(el) {
        console.log(el);
        var idcard = $(el).val();
//        if (idcard.length === 13) {
        $.ajax({
            url: '<?= $checkIdcardUrl ?>',
            data: {idcard: idcard},
            method: 'GET',
            dataType: 'JSON',
            success: function (res, textStatus, jqXHR) {
                console.log(res);
                _person = res;
                if (res.title.id) {
                    $('#<?= $elTitle ?>').val(res.title.id).trigger('change');
                }
//                    $('#<?= $elTitleEng ?>').val(res.title.name_eng);
                $('#<?= $elFirstName ?>').val(res.first_name);
                $('#<?= $elLastName ?>').val(res.last_name);
                $('#<?= $elFirstNameEng ?>').val(res.first_name_eng);
                $('#<?= $elLastNameEng ?>').val(res.last_name_eng);
                $('#<?= $elEmail ?>').val(res.email);
                $('#<?= $elTel ?>').val(res.tel);
                $('#<?= $elMobile ?>').val(res.mobile_no);
                $('#<?= $elOrg ?>').val(res.organization_id).trigger('depdrop:change').trigger('change');
                $('#<?= $elCvFile ?>').val(res.cv_file);
                $('#<?= $elAcceptPolicy ?>').val(res.accept_policy);

            },
            error: function (jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function () {});
            }
        });
//        }
    }
</script>