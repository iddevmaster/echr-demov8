<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Agenda;
use bajadev\ckeditor\CKEditor;
use kartik\widgets\AlertBlock;
use kartik\widgets\Growl;
use app\models\Risk;
use kartik\datecontrol\DateControl;
//
//\kartik\date\DatePickerAsset::register($this);
//\app\assets\HotkeysAsset::register($this);
//kartik\daterange\MomentAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="meeting-agenda-form">
    <?php $form = ActiveForm::begin();
    ?>
    <table class="table table-bordered table-condensed">
        <thead>
                        <tr>
                            <td colspan="2"><?= $form->field($model->submission, 'status')->radioList(app\models\Submission::getStatusCheckResult()) ?></td>
            </tr>
            <tr>
                <th><span class="font-weight-900"><?= Yii::t('app', 'วาระที่') ?></span> <?= $model->sort_label ?></th>
                <th><span class="font-weight-900"><?= Yii::t('app', 'เลขที่โครงการ') ?></span> <?= $model->submission->project->project_code ?> <?= $model->submission->project->is_child_project ? " (" . Yii::t('app', 'เด็ก') . ")" : "" ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาไทย)') ?></span> <?= $model->submission->project->name_thai ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาอังกฤษ)') ?></span> <?= $model->submission->project->name_eng ?></td>
            </tr>
            <tr>
                <td><span class="font-weight-900"><?= Yii::t('app', 'ผู้วิจัย'); ?></span> <?= $model->submission->project->projectLeader->person->fullName ?></td>
                <td><span class="font-weight-900"><?= Yii::t('app', 'สังกัด'); ?></span> <?= $model->submission->project->projectLeader->person->fullOrg ?></td>
            </tr>
            <?php
            $people = $model->coiPeople;
            if (count($people) > 0):
                ?>
                <tr>
                    <td colspan="2">
                        <span class="font-weight-900">COI</span>
                        <?php
                        $names = ArrayHelper::getColumn($people, 'fullName');
                        echo implode(', ', $names);
                        ?>

                    </td>
                </tr>
                <?php
            endif;
            ?>
        </tbody>
    </table>
    
    <?php
    $descId = uniqid();
    echo $form->field($model, 'description')->widget(CKEditor::className(), [
        'editorOptions' => [
            'preset' => 'standard',
            'inline' => false,
            'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
        ],
    ]);
    ?>
    <div class="issue1">
        <?php
        echo $form->field($model->submission, 'issue1')->widget(CKEditor::className(), [
            'editorOptions' => [
                'preset' => 'standard',
                'inline' => false,
                'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
            ],
        ]);
        ?>
    </div>
    <?php if ($model->need_resolution): ?>
        <?=
        $form->field($model, 'conclusion')->widget(CKEditor::className(), [
            'editorOptions' => [
                'preset' => 'standard',
                'inline' => false,
                'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
            ],
        ]);
        ?>
        <?php
        if ($model->submission->submissionType->risk_assessment) {
            echo $form->field($model->submission, 'risk_id')->widget(Select2::className(), [
                'data' => ArrayHelper::map(Risk::find()->isDeleted(FALSE)->all(), 'id', 'name'),
                'options' => ['placeholder' => ''],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        }
        if ($model->submission->submissionType->progress):
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model->submission, 'progress_period')->textInput(['type' => 'number']); ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($model->submission, 'next_progress_at')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                    ])
                    ?>
                </div>
            </div>
            <?php
        endif;
        if ($model->submission->submissionType->certify):
            ?>
            <div class="row">


                <div class="col-md-6">
                    <?=
                    $form->field($model->submission, 'certified_date')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                    ])
                    ?>
                </div>
                <div class="col-md-6">
                    <?=
                    $form->field($model->submission, 'expire_at')->widget(DateControl::className(), [
                        'type' => DateControl::FORMAT_DATE,
                    ])
                    ?>
                </div>
            </div>
            <?php
        endif;
        $resN = \app\models\Submission::RESOLUTION_N;
        echo $form->field($model, 'resolution')->widget(Select2::className(), [
            'data' => $model->submission->resolutionLabels,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'pluginEvents' => [
                "change" => "function() {
                    var data = $(this).select2('data');
//                    console.log(data);
                    if (data.length > 0 && data[0].id) {
                        if (data[0].id == '{$resN}') {
                            $('.issue2').show();
                        } else {
                            $('.issue2').hide();
                        }
                    }
                }",
            ],
        ]);
        ?>

        <div class="issue2" style="<?= $model->resolution == \app\models\Submission::RESOLUTION_N ? "" : "display:none;" ?>">
            <?php
            echo $form->field($model->submission, 'issue2')->widget(CKEditor::className(), [
                'editorOptions' => [
                    'preset' => 'standard',
                    'inline' => false,
                    'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
                ],
            ]);
            ?>
        </div>
        <?php
        echo $form->field($model, 'summary')->widget(CKEditor::className(), [
            'editorOptions' => [
                'preset' => 'standard',
                'inline' => false,
                'language' => Yii::$app->language,
//            'filebrowserBrowseUrl' => 'browse-images',
//            'filebrowserUploadUrl' => 'upload-images',
//            'extraPlugins' => 'imageuploader',
            ],
        ]);
        ?>

    <?php endif; ?>
    <?php ActiveForm::end(); ?>

</div>

<?php
$committeeCountUrl = \yii\helpers\Url::to(['meeting-agenda/add-committee-count', 'id' => $model->id]);
$certifiedDate = Html::getInputId($model->submission, 'certified_date');
$expireAt = Html::getInputId($model->submission, 'expire_at');
$certifiedDateVal = isset($model->submission->certified_date) ? Yii::$app->formatter->asDate($model->submission->certified_date) : "";
//\yii\helpers\VarDumper::dump($certifiedDateVal);
//exit;
$expireAtVal = isset($model->submission->expire_at) ? Yii::$app->formatter->asDate($model->submission->expire_at) : "";
$elProgressPeriod = Html::getInputId($model->submission, 'progress_period');
$elNextProgress = Html::getInputId($model->submission, 'next_progress_at');

