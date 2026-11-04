<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\SaeAssessForm;
use yii\helpers\ArrayHelper;
use app\models\Submission;
use app\models\Resolution;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\SaeAssessForm */
/* @var $form yii\widgets\ActiveForm */
$currentRole = \Yii::$app->session->get('currentRole');
?>

<div class="sae-assess-form-form">

    <?php
    $form = ActiveForm::begin([
                'layout' => 'inline',
                'id' => 'submission-type-assess-form',
                'action' => Url::to(['sae-assess-form/create', 'submissionId' => $model->submission_id, 'submissionCommitteeId' => $model->submission_committee_id]),
    ]);
    ?>
    <?= $this->renderFile('@app/views/widgets/_alert.php'); ?>

    <div class="border text-center">

        <?= Yii::t('app', 'แบบประเมินรายงานเหตุการณ์ไม่พึงประสงค์ (Serious Adverse Event: SAE)'); ?>
    </div>
    <div class="padding-top-20" >
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'เลขที่โครงการ'); ?></span> <?= $model->submission->project->project_code ?>
    </div>
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'ชื่อโครงการ'); ?></span> <?= $model->submission->project->name_thai ?>
    </div>
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'ชื่อหัวหน้าโครงการ'); ?></span> <?= $model->submission->projectLeader->person->i18nFullName ?>
        <span class="font-weight-900"><?= Yii::t('app', 'สังกัด'); ?></span> <?= $model->submission->projectLeader->person->divisionName ?>
    </div>
    <div>
        <span class="font-weight-900"><?= Yii::t('app', 'ชื่อกรรมการประเมิน'); ?></span> <?= $model->submissionCommittee->person->i18nFullName ?>
        <span class="font-weight-900"><?= Yii::t('app', 'กำหนดส่งคืน'); ?></span> <?= Yii::$app->formatter->asDate($model->submission->send_plan_date); ?>
    </div>
</div>
    <div class="font-weight-900 padding-top-20">
        <?= Yii::t('app', 'สรุปผลการประเมินเหตุการณ์ไม่พึงประสงค์ในโครงการวิจัยที่ได้รับการรับรองจากสำนักงานคณะกรรมการจริยธรรมฯเพื่อนำเสนอ ในที่ประชุมพิจารณาโครงการฯ'); ?>
    </div>
    <div>
        <?= Yii::t('app', 'จำนวนอาสาสมัครที่เกิดเหตุการณ์ไม่พึงประสงค์') ?>
        <?=
        $form->field($model, 'sae_total')->widget(MaskedInput::class, [
            'options' => [
                'class' => 'form-control',
            ],
            'clientOptions' => [
                'alias' => 'numeric',
                'prefix' => '',
                'groupSeparator' => ',',
                'allowMinus' => false,
                'autoGroup' => true,
                'autoUnmask' => true,
                'unmaskAsNumber' => false,
            ]
        ])
        ?>
        <?= Yii::t('app', 'ราย') ?>
    </div>

    <div>
        <?= Yii::t('app', '1. เป็นการรายงานเหตุการณ์ไม่พึงประสงค์จากสถาบันในต่างประเทศทั้งสิ้น'); ?> <?= Yii::t('app', 'จำนวน') ?>
        <?=
        $form->field($model, 'sae_for')->widget(MaskedInput::class, [
            'options' => [
                'class' => 'form-control',
            ],
            'clientOptions' => [
                'alias' => 'numeric',
                'prefix' => '',
                'groupSeparator' => ',',
                'allowMinus' => false,
                'autoGroup' => true,
                'autoUnmask' => true,
                'unmaskAsNumber' => false,
            ]
        ])
        ?><?= Yii::t('app', 'ราย') ?>,
        <?= Yii::t('app', 'ในจำนวนนี้เสียชีวิต') ?>
        <?=
        $form->field($model, 'sae_for_fatal')->widget(MaskedInput::class, [
            'options' => [
                'class' => 'form-control',
            ],
            'clientOptions' => [
                'alias' => 'numeric',
                'prefix' => '',
                'groupSeparator' => ',',
                'allowMinus' => false,
                'autoGroup' => true,
                'autoUnmask' => true,
                'unmaskAsNumber' => false,
            ]
        ])
        ?>
        <?= Yii::t('app', 'ราย') ?>
    </div>

    <div>
        <?= Yii::t('app', '2. มีอาสาสมัครเกิดเหตุการณ์ไม่พึงประสงค์จากสถาบันอื่นในประเทศไทย'); ?> <?= Yii::t('app', 'จำนวน') ?>
        <?=
        $form->field($model, 'sae_dom')->widget(MaskedInput::class, [
            'options' => [
                'class' => 'form-control',
            ],
            'clientOptions' => [
                'alias' => 'numeric',
                'prefix' => '',
                'groupSeparator' => ',',
                'allowMinus' => false,
                'autoGroup' => true,
                'autoUnmask' => true,
                'unmaskAsNumber' => false,
            ]
        ])
        ?><?= Yii::t('app', 'ราย') ?>
    </div>

    <div>
        <?= Yii::t('app', '3. มีอาสาสมัครเกิดเหตุการณ์ไม่พึงประสงค์จากโรงพยาบาลศรีนครินทร์ หรือสถาบันที่ได้รับการรับรองจากสำนักงานคณะกรรมการจริยธรรมการวิจัยในมนุษย์ มหาวิทยาลัยขอนแก่น'); ?> <?= Yii::t('app', 'จำนวน') ?>
        <?=
        $form->field($model, 'ec')->widget(MaskedInput::class, [
            'options' => [
                'class' => 'form-control',
            ],
            'clientOptions' => [
                'alias' => 'numeric',
                'prefix' => '',
                'groupSeparator' => ',',
                'allowMinus' => false,
                'autoGroup' => true,
                'autoUnmask' => true,
                'unmaskAsNumber' => false,
            ]
        ])
        ?><?= Yii::t('app', 'ราย') ?>
    </div>

    <div class="font-weight-900 padding-top-20">
        กรณีการเกิดเหตุการณ์ไม่พึงประสงค์ในข้อ 3
    </div>

    <div class="text-center">
        <?php
        echo $form->field($model, 'hasEcFatal')->label(false)->radioList(SaeAssessForm::getHasEcFatalLabels(), [
            'unselect' => NULL,
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                $id = "hasEcFatal-{$value}";
                $res = '';
                $style = '';

                $res .= Html::tag('div', Html::radio($name, $checked, [
                                    'id' => $id,
                                    'value' => $value
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "radio-custom radio-primary",
                            'style' => $style,
                ]);
                if ($value) {
                    $res .= Yii::t('app', 'จำนวน');
                    $res .= $form->field($model, 'ec_fatal')->widget(MaskedInput::class, [
                        'options' => [
                            'class' => 'form-control',
                        ],
                        'clientOptions' => [
                            'alias' => 'numeric',
                            'prefix' => '',
                            'groupSeparator' => ',',
                            'allowMinus' => false,
                            'autoGroup' => true,
                            'autoUnmask' => true,
                            'unmaskAsNumber' => false,
                        ]
                    ]);
                    $res .= Yii::t('app', 'ราย');
                }
                return $res;
            }
        ]);
        ?>
    </div>

    <div>
        <span><?= Yii::t('app', 'อาสาสมัครได้รับการรักษาจนเป็นปกติหรือไม่') ?></span>
        <?php
        echo $form->field($model, 'cureSelections')->label(false)->checkboxList(SaeAssessForm::getCureLabels(), [
            'unselect' => NULL,
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                $id = "cureSelections-{$value}";
                $res = '';
                $style = '';

                $res .= Html::tag('div', Html::checkbox($name, $checked, [
                                    'id' => $id,
                                    'value' => $value
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "checkbox-custom checkbox-primary",
                            'style' => $style,
                ]);
                $field = SaeAssessForm::getCureField($value);
                $res .= Yii::t('app', 'จำนวน');
                $res .= $form->field($model, $field)->widget(MaskedInput::class, [
                    'options' => [
                        'class' => 'form-control',
                    ],
                    'clientOptions' => [
                        'alias' => 'numeric',
                        'prefix' => '',
                        'groupSeparator' => ',',
                        'allowMinus' => false,
                        'autoGroup' => true,
                        'autoUnmask' => true,
                        'unmaskAsNumber' => false]
                ]);
                $res .= Yii::t('app', 'ราย');

                return $res;
            }
        ]);
        ?>
    </div>
    <div>
        <span><?= Yii::t('app', 'ผู้วิจัยประเมินเบื้องต้นสัมพันธ์กับยาวิจัยหรือไม่') ?></span>
        <?php
        echo $form->field($model, 'drugSelections')->label(false)->checkboxList(SaeAssessForm::getCureLabels(), [
            'unselect' => NULL,
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                $id = "drugSelections-{$value}";
                $res = '';
                $style = '';

                $res .= Html::tag('div', Html::checkbox($name, $checked, [
                                    'id' => $id,
                                    'value' => $value
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "checkbox-custom checkbox-primary",
                            'style' => $style,
                ]);
                $field = SaeAssessForm::getDrugField($value);
                $res .= Yii::t('app', 'จำนวน');
                $res .= $form->field($model, $field)->widget(MaskedInput::class, [
                    'options' => [
                        'class' => 'form-control',
                    ],
                    'clientOptions' => [
                        'alias' => 'numeric',
                        'prefix' => '',
                        'groupSeparator' => ',',
                        'allowMinus' => false,
                        'autoGroup' => true,
                        'autoUnmask' => true,
                        'unmaskAsNumber' => false,
                    ]
                ]);
                $res .= Yii::t('app', 'ราย');

                return $res;
            }
        ]);
        ?>
    </div>

    <div class="padding-top-20">
        <?= Yii::t('app', 'ข้อคิดเห็นเพิ่มเติม') ?> :
    </div>
    <div>
        <?= $form->field($model, 'suggestion', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]) ?>
    </div>

    <div class="padding-top-20">
        <?= Yii::t('app', 'มติของกรรมการ'); ?>
    </div>
    <div>
        <?php
        echo $form->field($model, 'resolution_id', ['options' => ['style' => 'width: 100%;']])->label(false)->radioList(ArrayHelper::map($resolutions, 'id', 'name'), [
            'unselect' => NULL,
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form) {
                $id = "resolution_id-{$value}";
                $res = '';
                $style = '';
                $resolution = Resolution::findOne($value);
                $res .= Html::tag('div', Html::radio($name, $checked, [
                                    'id' => $id,
                                    'value' => $value
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "radio-custom radio-primary",
                            'style' => $style,
                ]);
                $res .= '<br>';
                if ($resolution->resolution == Submission::RESOLUTION_C) {
                    $res .= $form->field($model, 'condition', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                    $res .= '<br>';
                } else if ($resolution->resolution == Submission::RESOLUTION_R || $resolution->resolution == Submission::RESOLUTION_N) {
                    $res .= Yii::t('app', 'ในประเด็น') . '<br>';
                    $res .= $form->field($model, 'addition', ['options' => ['style' => 'width: 100%;']])->textarea(['style' => 'width: 100%;', 'rows' => 6]);
                    $res .= '<br>';
                }
                return $res;
            }
        ]);
        ?>
    </div>
    <?php if (($currentRole['role_id'] == \app\models\Role::STAFF || $currentRole['role_id'] == \app\models\Role::ADMIN) || ($currentRole['role_id'] == \app\models\Role::COMMITTEE && $model->submissionCommittee->status == app\models\SubmissionCommittee::STATUS_ACCEPTED)) { ?>
        <div class="form-group margin-15">
            <?= Html::submitButton(Yii::t('app', 'บันทึก'), ['class' => 'btn btn-primary btn-assess-form-save']) ?>
        </div>
        <?php if (isset($model->id)) { ?>
            <a href="<?= Url::to(['sae-assess-form/print-pdf', 'submissionId' => $model->submission_id, 'submissionCommitteeId' => $model->submission_committee_id]) ?>" data-pjax="0" style="text-decoration: none" target="_blank">
                <button type="button" class="btn btn-default"><i class="icon wb-print" aria-hidden="true"></i> EXPORT PDF</button>
            </a>
        <?php } ?>
    <?php } ?>


    <?php ActiveForm::end(); ?>

</div>
