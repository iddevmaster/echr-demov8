<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Panel;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
use kartik\datecontrol\DateControl;
use bajadev\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model app\models\Submission */
/* @var $form yii\widgets\ActiveForm */

$currentRole = \Yii::$app->session->get('currentRole');
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
    <div class="col-md-12">
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(app\models\PersonRolePanel::find()->joinWith(['personRole', 'personRole.person'])->isDeleted(FALSE)->andWhere(['person_role.role_id' => app\models\Role::STAFF, 'person.deleted' => 0])->orderBy('person_role.id')->all(), 'personRole.person.user_id', 'personRole.person.fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($model, 'responsible_person')->label(Yii::t('app', 'เลือกเจ้าหน้าที่ที่รับผิดชอบ'))->widget(Select2::classname(), [
            'data' => $data,
            'options' => ['placeholder' => ''],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        ?>
    </div>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>