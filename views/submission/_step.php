<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\Organization;
use yii\helpers\ArrayHelper;
use app\models\FundingSource;
use app\models\SubmissionType;
use yii\bootstrap\Modal;
?>
<style>

    element.style {
    }
    .step.current .step-number {
        color: #667afa;
        background-color: #fff;
    }
    .step.current .step-number, .step.active .step-number {
        color: #3e8ef7;
        background-color: #fff;
    }
    .step-number {
        color: #fff;
        background: #e4eaec;
    }
    .step-number-right {
        position: absolute;
        top: 50%;
        right: 20px;
        height: 40px;
        font-size: 24px;
        line-height: 40px;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
    }
</style>
<?php 
            $i = 1;
            $sc = app\models\Submission::find()->isDeleted(false)->andWhere(['submission.id' => $submission->id])->one();
            if ($sc->is_meeting == '2') {
                $sh = \app\models\SubmissionStatusHistory::find()->submission($sc->id)->status(\app\models\Submission::STATUS_DOC_REJECTED_BY_COMMITTEE)->one();
                if (isset($sh)) {
                    $models = app\models\Submission::getStatusStepC160();
                    $p = 3;
                } else {
                    $models = app\models\Submission::getStatusStepC();
                    $p = 4;
                }
            } else {
                $models = app\models\Submission::getStatusStep();
                $p = 3;
            }
?>
<div class="register-transaction-create col-md-12">
    <div class="panel" id="exampleWizardFormContainer">
        <div class="panel-heading">
            <h3 class="panel-title"> </h3>
        </div>
        <div class="panel-body">
            <!-- Steps -->
            <div class="pearls row">
                <?php foreach ($models as $model): 
                    $customValue = \app\models\Submission::getCustomStatusValueStep($model);
                    ?>
                    <div class="pearl col-xs-3 <?php if (($model == $sc->status || ($customValue['min'] <= $sc->status && $customValue['max'] >= $sc->status ))) { ?> current <?php } else { ?>

        <?php if ((!isset($customValue) && $sc->status >= $model) || (isset($customValue) && ($sc->status >= $customValue['min']))) { ?>
                             done 
                             <?php
                         }
                     }
                     ?>"  text-center">
                        <div class="pearl-icon"><?php if (($model == $sc->status || ($customValue['min'] <= $sc->status && $customValue['max'] >= $sc->status ))) { ?> <i class="glyphicon glyphicon-edit" aria-hidden="true"></i> <?php } else { ?>

        <?php if ((!isset($customValue) && $sc->status >= $model) || (isset($customValue) && ($sc->status >= $customValue['min']))) { ?>
                             <i class="glyphicon glyphicon-check" aria-hidden="true"></i> 
                             <?php
                         }
                     }
                     ?></div>
                        <span class="pearl-title"><?= app\models\Submission::getStatusStepLabel()[$model] ?></span>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>





