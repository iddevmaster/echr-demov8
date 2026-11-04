<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\QuestionnaireTitle;
use bajadev\ckeditor\CKEditor;
use app\models\SubmissionType;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Tambon */
?>
<div class="assessment">
    <?php Pjax::begin(['id' => 'submission-type-assess-form-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE]); ?>
    <?php
    if ($staf->assess_form == SubmissionType::FORM_CONTINUE) {
        echo $this->renderFile('@app/views/continue-assess-form/create.php', [
            'model' => $assessForm,
            'ethicses' => $assessFormParams['ethicses'],
            'conEthicses' => $assessFormParams['conEthicses'],
            'reviewChoices' => $assessFormParams['reviewChoices'],
            'resolutions' => $assessFormParams['resolutions'],
        ]);
    } else if ($staf->assess_form == SubmissionType::FORM_SAE) {
        echo $this->renderFile('@app/views/sae-assess-form/summary.php', [
            'submission' => $submission,
            'sCommitteeId' => $sCommitteeId,
//            'model' => $assessForm,
//            'resolutions' => $assessFormParams['resolutions'],
        ]);
        echo $this->renderFile('@app/views/sae-assess-form/create.php', [
            'model' => $assessForm,
            'resolutions' => $assessFormParams['resolutions'],
        ]);
    } else if ($staf->assess_form == SubmissionType::FORM_C) {
        echo $this->renderFile('@app/views/c-assess-form/create.php', [
            'model' => $assessForm,
        ]);
    } else if ($staf->assess_form == SubmissionType::FORM_DEVIATION) {
        echo $this->renderFile('@app/views/deviation-assess-form/summary.php', [
            'submission' => $submission,
            'sCommitteeId' => $sCommitteeId,
//            'model' => $assessForm,
//            'resolutions' => $assessFormParams['resolutions'],
        ]);
        echo $this->renderFile('@app/views/deviation-assess-form/create.php', [
            'model' => $assessForm,
            'reviewChoices' => $assessFormParams['reviewChoices'],
            'resolutions' => $assessFormParams['resolutions'],
        ]);
    }
    ?>
    <?php Pjax::end(); ?>
</div>
