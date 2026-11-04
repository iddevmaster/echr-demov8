<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SubmissionVolunteer;
use app\models\SubmissionVolunteerSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\GalleryImage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="submission-document-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($model) ?>
    <?= $form->field($model, 'status')->radioList(\app\models\SubmissionDocument::getStatusLabels()) ?>
    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <?php
    if (isset($subEvent)) :
        $event = ArrayHelper::map(\app\models\DeviationType::find()->isDeleted(false)->all(), 'id', 'name');
        ?>   
        <?= $form->field($subEvent, 'code')->textInput([]) ?>

        <?php
//            \yii\helpers\VarDumper::dump($subEvent->deviationTypes, 10, true);
        echo $form->field($subEvent, 'deviationTypes')->checkboxList($event, [
            'unselect' => NULL,
            'separator' => '<br>',
            'item' => function ($index, $label, $name, $checked, $value) use ($model, $form, $subEvent) {
//                \yii\helpers\VarDumper::dump($value, 10, true);
//                \yii\helpers\VarDumper::dump($name, 10, true);
                $id = "deviationTypes-{$value}";
                $res = '';
                $style = '';
                $rc = \app\models\DeviationType::findOne($value);
//                            if ($value == BioForm::TOS_OTHERS || $value == BioForm::TOS_CLINICAL_TRIAL) {
//                                $res .= '<br>';
//                                $label .= "<i class='margin-left-20'>Please specify</i>";
//                                $style = "display: table-cell !important;white-space: nowrap;";
//                            }
                $res .= Html::tag('div', Html::checkbox($name, $checked, [
                                    'id' => $id,
                                    'value' => $value,
                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                            'class' => "checkbox-custom checkbox-primary"
                ]);
                if (($rc->id == 15)) {
                    $res .= $form->field($subEvent, 'other', [
                                'options' => [
                                ]
                            ])->label(false)->textInput();
                }

                return $res;
            }
        ]);
        ?>    

    <?php endif; ?>

    <?php if (isset($subVol)) : ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($subVol, 'volunteerCode')->textInput() ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($subVol, 'type')->dropDownList(SubmissionVolunteer::typeLabels()) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($subVol, 'follow_up_no')->textInput(['type' => 'number']) ?>
            </div>
        </div>
        <div><?= Yii::t('app', 'ประวัติอาสาสมัคร') ?></div>
        <?php
//        $histories = $subVol->histories;
        $subVolProvider = new ArrayDataProvider([
            'allModels' => $subVol->histories,
            'modelClass' => SubmissionVolunteer::class,
        ]);
        echo $this->renderFile('@app/views/submission-volunteer/history.php', [
            'dataProvider' => $subVolProvider,
        ]);
        ?>
    <?php endif; ?>
    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::button(Yii::t('app', "บันทึก"), ['class' => 'btn btn-primary', 'type' => "submit"]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>