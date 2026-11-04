<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use app\models\Meeting;
use app\models\MeetingAgenda;
use app\models\SubmissionCommittee;
use yii\helpers\Url;

?>
<div class="submission-committee-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
//                            'target' => '#crud-datatable-ticket-h-pjax'
                   // 'class' => 'form-inline'
                ],
//                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <div class="row">
        <div class="col-lg-6">
        <?php
        // Usage with ActiveForm and model
        $data = ArrayHelper::map(Meeting::find()->isDeleted(FALSE)->orderBy('CONVERT(meeting.id USING TIS620) desc')->all(), 'id', 'fullName');
//    \yii\helpers\VarDumper::dump($data, 10, TRUE);
        echo $form->field($searchModel, 'meetingId')->label(FALSE)->widget(Select2::classname(), [
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
        if (isset($searchModel->submission_id)) {
//            yii\helpers\VarDumper::dump($searchModel->submission_id);
//            $sub = \app\models\Submission::findOne($searchModel->submission_id);
            $data = [$searchModel->submission_id => $searchModel->submission->project->project_code];
        }
            echo $form->field($searchModel, 'submission_id')->label(FALSE)->widget(DepDrop::className(), [
                'type' => DepDrop::TYPE_SELECT2,
                'data' => $data,
                'options' => ['placeholder' => yii::t('app', 'เลือกประเภทการส่ง')],
                'select2Options' => [
                    'pluginOptions' => ['allowClear' => true]
                ],
                'pluginOptions' => [
                    'depends' => [Html::getInputId($searchModel, 'meetingId')],
                    'url' => Url::to(['/meeting-agenda/list']),
                    'placeholder' => '',
                ],
            ]);
            ?>
        </div>
</div>

    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>