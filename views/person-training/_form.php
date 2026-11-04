<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\TrainingType;

/* @var $this yii\web\View */
/* @var $model app\models\PersonTraining */
/* @var $form yii\widgets\ActiveForm */

$trainingTypes = TrainingType::find()->isDeleted(false)->orderBy('id')->all();
$validityMap = [];
foreach ($trainingTypes as $tt) {
    $validityMap[$tt->id] = (int) $tt->validity_years;
}
?>

<div class="person-training-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <?= Yii::t('app', '<div class="red-600">หมายเหตุ : เอกสารจะต้องลงนามและวันที่กำกับด้วย</div>') ?>

    <?=
    $form->field($model, 'training_type_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map($trainingTypes, 'id', 'name'),
        'options' => ['placeholder' => Yii::t('app', 'เลือกประเภทการอบรม')],
        'pluginOptions' => ['allowClear' => true],
    ]);
    ?>

    <?= $form->field($model, 'name_thai_course')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_eng_course')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'start_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE
    ]);
    ?>

    <?= $form->field($model, 'expire_date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'options' => ['disabled' => true],
    ])->hint(Yii::t('app', 'คำนวณอัตโนมัติจากวันที่อบรม + ระยะเวลาที่ใช้ได้ของประเภทการอบรม')) ?>

    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>


    <div class="row">
        <div class="col-lg-12">
            <?= Yii::t('app', '<div class="red-600">หมายเหตุ : เอกสารจะต้องลงนามและวันที่กำกับด้วย</div>') ?>
            <?=
            $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => [
                    'accept' => '*',
                //'multiple' => 'true',
                ],
                'pluginOptions' => [
                    'theme' => 'gly',
                    'showUpload' => false,
                    'browseLabel' => '',
                    'removeLabel' => '',
                    'initialPreview' => [
                        // $model->FileUrl
                        Url::to(['person-training/download', 'id' => $model->id])
                    ],
                    'initialPreviewAsData' => FALSE,
                    'initialCaption' => $model->file,
                    'initialPreviewConfig' => [
                            ['caption' => $model->file]
                    ],
                //'overwriteInitial' => false
                ],
            ]);
            ?>

        </div>

    </div>


    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>
<?php
$startDate = Html::getInputId($model, 'start_date');
$startDateVal = isset($model->start_date) ? Yii::$app->formatter->asDate($model->start_date) : "";
$endDate = Html::getInputId($model, 'end_date');
$endDateVal = isset($model->end_date) ? Yii::$app->formatter->asDate($model->end_date) : "";
$expireDate = Html::getInputId($model, 'expire_date');
$expireDateVal = isset($model->expire_date) ? Yii::$app->formatter->asDate($model->expire_date) : "";
$typeId = Html::getInputId($model, 'training_type_id');
$validityJson = \yii\helpers\Json::encode($validityMap);
$js = <<<js
        $('#{$startDate}-disp-kvdate').kvDatepicker('update', '{$startDateVal}');
        $('#{$endDate}-disp-kvdate').kvDatepicker('update', '{$endDateVal}');
        $('#{$expireDate}-disp-kvdate').kvDatepicker('update', '{$expireDateVal}');

        var _validity = {$validityJson};
        // Parse a start date from the display field (d/m/Y) or hidden ISO field.
        function _parseStart() {
            var disp = $('#{$startDate}-disp-kvdate').val();
            if (disp) {
                var m = disp.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
                if (m) { return new Date(parseInt(m[3],10), parseInt(m[2],10)-1, parseInt(m[1],10)); }
            }
            var iso = $('#{$startDate}').val();
            if (iso) {
                var d = new Date(iso);
                if (!isNaN(d.getTime())) { return d; }
            }
            return null;
        }
        function _recalcExpire() {
            var typeId = $('#{$typeId}').val();
            var d = _parseStart();
            var years = _validity[typeId];
            if (!typeId || !d || !years) {
                $('#{$expireDate}').val('');
                $('#{$expireDate}-disp-kvdate').kvDatepicker('update', '');
                return;
            }
            d.setFullYear(d.getFullYear() + parseInt(years, 10));
            var iso = d.getFullYear() + '-' +
                ('0' + (d.getMonth() + 1)).slice(-2) + '-' +
                ('0' + d.getDate()).slice(-2);
            $('#{$expireDate}').val(iso);
            // display field expects the configured display format (d/m/Y)
            var disp = ('0' + d.getDate()).slice(-2) + '/' +
                ('0' + (d.getMonth() + 1)).slice(-2) + '/' + d.getFullYear();
            $('#{$expireDate}-disp-kvdate').kvDatepicker('update', disp);
        }
        $('#{$typeId}').on('change', _recalcExpire);
        $('#{$startDate}').on('change', _recalcExpire);
        $('#{$startDate}-disp-kvdate').on('changeDate change', _recalcExpire);
js;
$this->registerJs($js);
