<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Agenda;
use bajadev\ckeditor\CKEditor;
use kartik\widgets\AlertBlock;
use kartik\widgets\Growl;

$currentRole = \Yii::$app->session->get('currentRole');

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
\app\assets\HotkeysAsset::register($this);
?>

<div class="meeting-agenda-form">

    <?php
    echo $this->renderFile('@app/views/widgets/_growl.php');
//    echo AlertBlock::widget([
//        'useSessionFlash' => true,
//        'type' => AlertBlock::TYPE_GROWL,
//        'delay' => FALSE,
//        'alertSettings' => [
//            'success' => [
//                'type' => kartik\alert\Alert::TYPE_SUCCESS,
//                'options' => [
//                    'class' => 'dark',
//                ],
//            ],
//            'danger' => [
//                'type' => kartik\alert\Alert::TYPE_DANGER,
//                'options' => [
//                    'class' => 'dark',
//                ],
//            ],
//        ]
//    ]);
    ?>
    <?php $form = ActiveForm::begin();
    ?>

    <div class="row update-meeting-agenda-info">
        <div class="pull-right">
            <?php if ((($model->meeting->checked_status == app\models\Meeting::CS_PENDING) && ($currentRole['role_id'] == \app\models\Role::STAFF && $model->meeting->checked_staff == Yii::$app->user->identity->id)) || (($model->meeting->checked_status == app\models\Meeting::CS_STAFF_CHECKED) && ($currentRole['role_id'] == \app\models\Role::SECRETARY))) { ?>
                <?= Html::button(Yii::t('app', 'บันทึก (Ctrl+S)'), ['class' => 'btn btn-primary btn-round btn-save-update-info']) ?>
            <?php } ?>
            <?php if (!isset($model->meeting->end_time) && $currentRole['role_id'] == \app\models\Role::STAFF) { ?>
                <?= Html::button(Yii::t('app', 'บันทึก (Ctrl+S)'), ['class' => 'btn btn-primary btn-round btn-save-update-info']) ?>
            <?php } ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'sort_label')->textInput(['readonly' => TRUE]); ?>
        </div>
        <div class="col-md-10">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?php
    echo $form->field($model, 'description')->widget(CKEditor::className(), [
//        'options' => [
//            'id' => uniqid(),
//        ],
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

    <?php if ($model->need_resolution): ?>

        <?= $form->field($model, 'resolution')->textInput(); ?>

        <?php
        echo $form->field($model, 'conclusion')->widget(CKEditor::className(), [
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
$js = <<<js
    
                
//    console.log(CKEDITOR.instances);
    Object.keys(CKEDITOR.instances).forEach(function(key) {
        CKEDITOR.instances[key].on( 'key', editorKeySave);
    });
js;
$this->registerJs($js);
