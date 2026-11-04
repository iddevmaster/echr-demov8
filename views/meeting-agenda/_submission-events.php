<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\SubmissionType;
use app\models\DeviationEvent;

/* @var $this yii\web\View */
/* @var $model app\models\MeetingAgenda */
/* @var $form yii\widgets\ActiveForm */

if ($meetingAgenda->submission->submission_type_id == SubmissionType::TYPE_DEVIATION):
    $events = $meetingAgenda->submission->getSubmissionEvents()->isDeleted(false)->all();
    ?>

    <div class="question-form">

        <?php foreach ($events as $event): ?>

            <ul class="list-group list-group-dividered list-group-full">

                <li class="list-group-item">
                    <h4 class="text-primary">
                        <?= Yii::t('app', 'เหตุการณ์ลำดับที่') ?> <?= $event->event_no ?>
                    </h4>

                    <div>
                        <?php
                        echo $form->field($event, "[{$event->id}]meeting_violation_type")->label(FALSE)->radioList(DeviationEvent::violationLabels(), [
                            'unselect' => NULL,
                            'item' => function ($index, $label, $name, $checked, $value) use ($event) {
                                $id = 'event_' . $event->id . "-" . $index;
                                return Html::tag('div', Html::radio($name, $checked, [
                                                    'id' => $id,
                                                    'value' => $value
                                                ]) . Html::label($label, $id, ['class' => 'padding-right-20']), [
                                            'class' => 'radio-custom radio-primary inline-block'
                                ]);
                            }
                        ]);
                        ?>
                    </div>
                </li>

            </ul>
        <?php endforeach; ?>

    </div>

<?php endif; ?>