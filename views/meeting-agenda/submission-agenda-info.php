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

\kartik\date\DatePickerAsset::register($this);
\app\assets\HotkeysAsset::register($this);
kartik\daterange\MomentAsset::register($this);
/* @var $this yii\web\View */
/* @var $ma app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="meeting-agenda-form">

    <?php
    echo AlertBlock::widget([
        'useSessionFlash' => true,
        'type' => AlertBlock::TYPE_ALERT,
        'delay' => FALSE,
        'alertSettings' => [
            'success' => [
                'type' => kartik\alert\Alert::TYPE_SUCCESS,
                'options' => [
                    'class' => 'dark',
                ],
            ],
            'danger' => [
                'type' => kartik\alert\Alert::TYPE_DANGER,
                'options' => [
                    'class' => 'dark',
                ],
            ],
        ]
    ]);
    ?>
    <?= $ma->submission->projectLeader->person->getAlertDeviationProtocolHtml() ?>
    <?php $form = ActiveForm::begin();
    ?>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th colspan="2"><span class="font-weight-900"><?= Yii::t('app', ' การประชุมครั้งที่ ') ?></span> <?= $ma->meeting->yearNo?> <span class="font-weight-900"><?= Yii::t('app', ' วาระที่') ?></span> <?= $ma->sort_label ?> <?php if(!empty($ma->submission->resolution)): ?><span class="font-weight-900"><?= Yii::t('app', ' ผลการพิจารณา') ?></span> <?= app\models\Submission::getResolutionLables()[$ma->submission->resolution]; ?> </th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาไทย)') ?></span> <?= $ma->submission->project->name_thai ?></td>
            </tr>
            <tr>
                <td colspan="2"><span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาอังกฤษ)') ?></span> <?= $ma->submission->project->name_eng ?></td>
            </tr>
            <tr>
                <td><span class="font-weight-900"><?= Yii::t('app', 'หัวหน้าโครงการวิจัย'); ?></span> <?= $ma->submission->project->projectLeader->person->fullName ?></td>
                <td><span class="font-weight-900"><?= Yii::t('app', 'สังกัด'); ?></span> <?= $ma->submission->project->projectLeader->person->fullOrg ?></td>
            </tr>
            <?php
            $people = $ma->coiPeople;
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
    <?php if (!empty($ma->description)): ?>
        <div class="panel">
            <div class="panel-heading bg-blue-100">
                <h3 class="panel-title"><?= Yii::t('app', 'รายละเอียด') ?></h3>
            </div>
            <div class="panel-body margin-15">
                <?= $ma->description ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($ma->submission->issue1)): ?>
        <div class="panel">
            <div class="panel-heading bg-yellow-100">
                <h3 class="panel-title"><?= Yii::t('app', 'ประเด็นการพิจารณาเพิ่มเติม 1') ?></h3>
            </div>
            <div class="panel-body margin-15">
                <?= $ma->submission->issue1 ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($ma->submission->issue2)): ?>
        <div class="panel">
            <div class="panel-heading bg-green-100">
                <h3 class="panel-title"><?= Yii::t('app', 'ประเด็นการพิจารณาเพิ่มเติม 2') ?></h3>
            </div>
            <div class="panel-body margin-15">
                <?= $ma->submission->issue2 ?>
            </div>
        </div>
    <?php endif; ?>
        <?php if (!empty($ma->summary)): ?>
        <div class="panel">
            <div class="panel-heading bg-red-100">
                <h3 class="panel-title"><?= Yii::t('app', 'หมายเหตุ') ?></h3>
            </div>
            <div class="panel-body margin-15">
                <?= $ma->summary ?>
            </div>
        </div>
    <?php endif; ?>
    <?=
    $this->render('_questions-info', [
        'answers' => $answers,
        'form' => $form,
        'meetingAgenda' => $ma,
    ])
    ?>

    <?php ActiveForm::end(); ?>

</div>