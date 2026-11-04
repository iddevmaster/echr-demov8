<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\ProjectQuestion;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form">

    <?php foreach ($answers as $key => $answer): ?>

        <ul class="list-group list-group-dividered list-group-full">

            <li class="list-group-item">
                <h4 class="text-primary">
                    <?= $answer->projectQuestion->name ?>
                </h4>

                <div>
                    <?php
                    $choices = ArrayHelper::map($answer->projectQuestion->getProjectQuestionChoices()->isDeleted(FALSE)->all(), 'project_type_id', 'projectType.name');
                    if ($answer->projectQuestion->answer_type == ProjectQuestion::TYPE_MULTI_CHOICES) {
                        echo $form->field($answer, "[{$key}]choices")->label(FALSE)->checkboxList($choices, [
                            'unselect' => NULL,
                            'item' => function ($index, $label, $name, $checked, $value) use ($answer) {
                                $id = 'question_' . $answer->project_question_id . "-" . $index;
                                $res = '';
                                //                        $res .= CheckboxX::widget([
                                //                            'name'=>$name,
                                //                            'options'=>['id'=>$id],
                                //                            'pluginOptions'=>['threeState'=>false]
                                //                        ]);
                                //                        $res .= '<label class="cbx-label padding-right-20" for="'.$id.'">'.$label.'</label>';
                                return Html::tag('div', Html::checkbox($name, $checked, [
                                                    'id' => $id,
                                                    'disabled' => true,
                                                    'value' => $value
                                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                            'class' => 'checkbox-custom checkbox-primary inline-block'
                                ]);
                            }
                        ]);
                    } else if ($answer->projectQuestion->answer_type == ProjectQuestion::TYPE_SINGLE_CHOICE) {
                        echo $form->field($answer, "[{$key}]choices")->label(FALSE)->radioList($choices, [
                            'unselect' => NULL,
                            'item' => function ($index, $label, $name, $checked, $value) use ($answer) {
                                $id = 'question_' . $answer->project_question_id . "-" . $index;
                                return Html::tag('div', Html::radio($name, $checked, [
                                                    'id' => $id,
                                                    'disabled' => true,
                                                    'value' => $value
                                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                            'class' => 'radio-custom radio-primary inline-block'
                                ]);
                            }
                        ]);
                    }
                    ?>
                </div>
            </li>

        </ul>
<?php endforeach; ?>

</div>
