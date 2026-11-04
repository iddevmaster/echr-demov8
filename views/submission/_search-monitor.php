<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Submission;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use app\models\Role;

$currentRole = \Yii::$app->session->get('currentRole');
if ($currentRole['role_id'] == Role::PRESIDENT || $currentRole['role_id'] == Role::COPRESIDENT) {
    $data = Submission::getStatusLabelsSearch();
} elseif ($currentRole['role_id'] == Role::COMMITTEE) {
    $data = Submission::getStatusLabelsCommitteeSearch();
} elseif ($currentRole['role_id'] == Role::RESEARCHER) {
    $data = Submission::getStatusLabelsResearcherSearch();
} elseif ($currentRole['role_id'] == Role::STAFF) {
    if (($searchModel->status == Submission::STATUS_DOC_REJECTED || $searchModel->status == Submission::STATUS_SUBMITTED || $searchModel->status == Submission::STATUS_DOC_APPROVED) && ($searchModel->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW)) {
        $data = Submission::getStatusLabelsNopanel();
    } elseif ($status == Submission::STATUS_SUBMITTED && $searchModel->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_CONT) {
        $data = Submission::getStatusLabelsCon();
    } else {
        $data = Submission::getStatusLabelsStaffSearch();
    }
} else {
    $data = Submission::getStatusLabels();
}
if (isset($staff)) {
    $staff = $staff;
} else {
    $staff = NULL;
}
?>
<div class="submission-search">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'action' => isset($url) ? base64_decode($url) : Url::to(['submission/index-ismonitor']),
//                'action' => isset($staff) ? Url::to(['submission/index','staff'=> isset($staff)? $staff:"",'status'=>isset($status)? $status : "",'typeGroup'=>isset($typeGroup)? $typeGroup : ""]) : "",
                'options' => [
                    'data-pjax' => 1,
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <?= $form->field($searchModel, 'responsible_person')->hiddenInput()->label(false); ?>
    <div class="row">
        <div class="col-lg-6"><?= $form->field($searchModel, 'name')->label(FALSE)->textInput(['placeholder' => yii::t('app', 'ค้นหาตามชื่อโครงการและหมายเลขโครงการ')]); ?></div>
        <?php if ($currentRole['role_id'] == Role::COMMITTEE) { ?>
            <div class="col-lg-6"><?php
                $dataCom = \app\models\SubmissionCommittee::getStatusLabelsCommittee();
                echo $form->field($searchModel, 'committeeStatus')->label(FALSE)->widget(Select2::classname(), [
                    'data' => $dataCom,
                    'options' => ['placeholder' => yii::t('app', 'เลือกสถานะ')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>  
            </div>

        <?php } else { ?>
            <div class="col-lg-6"><?php
                echo $form->field($searchModel, 'status')->label(FALSE)->widget(Select2::classname(), [
                    'data' => $data,
                    'options' => ['placeholder' => yii::t('app', 'เลือกสถานะ')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>  
            </div>      
        <?php } ?>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($searchModel, 'personName')->label(FALSE)->textInput(['placeholder' => yii::t('app', 'ค้นหาตามชื่อหัวหน้าโครงการหรือผู้ร่วมวิจัย')]); ?>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <?php $datas = ArrayHelper::map(\app\models\SubmissionTypeGroup::find()->isDeleted(FALSE)->orderBy('CONVERT(name USING TIS620)')->all(), 'id', 'i18nName'); ?>
            <?=
            $form->field($searchModel, 'submission_type_group_id')->label(FALSE)->widget(Select2::className(), [
                'data' => $datas,
                'options' => ['placeholder' => yii::t('app', 'เลือกกลุ่มการส่ง')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div> 
        <div class="col-lg-3">
            <?php
            $data = [];
            if (!empty($searchModel->submission_type_group_id)) {
                $data = ArrayHelper::map(app\models\SubmissionType::find()->isDeleted(FALSE)->group($searchModel->submission_type_group_id)->orderBy('CONVERT(submission_type.name USING TIS620)')->all(), 'id', 'i18nName');
                // \yii\helpers\VarDumper::dump($data, 10, TRUE);    
            }
            ?>
            <?=
            $form->field($searchModel, 'submission_type_id')->label(FALSE)->widget(DepDrop::className(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => [
                    'placeholder' => yii::t('app', 'เลือกประเภทการส่ง'),
                    'class' => Yii::$app->util->getFormControlClass($searchModel->submission_type_id)
                ],
                'select2Options' => [
                    'pluginOptions' => ['allowClear' => FALSE]
                ],
                'pluginOptions' => [
                    'depends' => [Html::getInputId($searchModel, 'submission_type_group_id')],
                    'url' => Url::to(['/submission-type/list']),
                    'placeholder' => '',
                ],
            ]);
            ?>
        </div>
        <div class="col-lg-3">
            <?php $datas = ArrayHelper::map(\app\models\PersonRolePanel::find()->joinWith('personRole')->isDeleted(FALSE)->person(\Yii::$app->user->identity->person->id)->all(), 'panel.id', 'panel.i18nName'); ?>
            <?=
            $form->field($searchModel, 'panel_id')->label(FALSE)->widget(Select2::className(), [
                'data' => $datas,
                'options' => ['placeholder' => yii::t('app', 'Panel')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
            ?>
        </div> 
        <div class="col-lg-2">        <?= $form->field($searchModel, 'is_legacy')->checkbox() ?>
        </div>
    </div>
    <div class="form-group text-right">
        <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>