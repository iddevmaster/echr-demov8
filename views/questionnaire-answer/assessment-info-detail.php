<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\QuestionnaireTitle;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Tambon */
//$this->title = Yii::t('app', 'ประเมินงานวิจัย') . $submission->project->name_thai;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายการโครงการวิจัย'), 'url' => ['submission/index', 'committeeStatus' => app\models\SubmissionCommittee::STATUS_ACCEPTED]];
////$this->params['breadcrumbs'][] = ['label' => 'เลือกประเภทโครงการวิจัย', 'url' => ['document-submission-type/index', 'roleId' => $roleId]];
//$this->params['breadcrumbs'][] = $this->title;
//
//foreach ($answers as $key => $answer):
//    $as = $answer->id;
//endforeach;
//foreach ($submissionDocs as $key => $doc):
//    $d = $doc->id;
//endforeach;
?>
<div class="assessment">
    <?php
    $form = ActiveForm::begin();
    //echo $form->errorSummary($answers);
    ?>
    <ul class="list-group list-group-dividered list-group-full">

        <?php foreach ($answers as $key => $answer): ?>
            <li class="list-group-item">
                <h4 class="text-primary">
                    <?= ($key + 1) . ". " . $answer->questionnaireTitle->title ?>
                </h4>

                <div>
                    <?php
                    $choices = ArrayHelper::map($answer->questionnaireTitle->getQuestionnaireChoices()->isDeleted(FALSE)->all(), 'id', 'title');
                    if ($answer->questionnaireTitle->questionnaire_type == QuestionnaireTitle::TYPE_MULTI_CHOICES) {
                        echo $form->field($answer, "[{$key}]choices", ['inline' => TRUE])->label(FALSE)->checkboxList($choices, [
                            'unselect' => NULL,
                            'item' => function ($index, $label, $name, $checked, $value) use ($answer) {
                                $id = 'question_' . $answer->questionnaire_title_id . "-" . $index;
                                $res = '';
                                //                        $res .= CheckboxX::widget([
                                //                            'name'=>$name,
                                //                            'options'=>['id'=>$id],
                                //                            'pluginOptions'=>['threeState'=>false]
                                //                        ]);
                                //                        $res .= '<label class="cbx-label padding-right-20" for="'.$id.'">'.$label.'</label>';
                                return Html::tag('div', Html::checkbox($name, $checked, [
                                                    'id' => $id,
                                                    'value' => $value,
                                                    'disabled' => true,
                                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                            'class' => 'checkbox-custom checkbox-primary inline-'
                                ]);
                            }
                        ]);
                    } else if ($answer->questionnaireTitle->questionnaire_type == QuestionnaireTitle::TYPE_SINGLE_CHOICE) {
                        echo $form->field($answer, "[{$key}]choices")->label(FALSE)->radioList($choices, [
                            'unselect' => NULL,
                            'item' => function ($index, $label, $name, $checked, $value) use ($answer) {
                                $id = 'question_' . $answer->questionnaire_title_id . "-" . $index;
                                return Html::tag('div', Html::radio($name, $checked, [
                                                    'id' => $id,
                                                    'disabled' => true,
                                                    'value' => $value
                                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                            'class' => 'radio-custom radio-primary inline-block'
                                ]);
                            }
                        ]);
                    } else if ($answer->questionnaireTitle->questionnaire_type == QuestionnaireTitle::TYPE_TEXT_CHOICE) {
//                        yii\helpers\VarDumper::dump($answer);
//                        yii\helpers\VarDumper::dump($key);
                        echo $form->field($answer, "[{$key}]text_answer")->label(FALSE)->textInput(['disabled' => 'disabled']);
                    }
                    ?>
                </div>
            </li>
<?php endforeach; ?>
    </ul>
        <?php ActiveForm::end(); ?>
</div>

