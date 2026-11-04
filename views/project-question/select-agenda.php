<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Agenda;

/* @var $this yii\web\View */
/* @var $model app\models\PersonRole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="select-agenda-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    $data = ArrayHelper::map(Agenda::find()->isDeleted(FALSE)->hasParent()->orderBy('parent_id ASC, sort ASC')->all(), 'id', 'fullName');
    echo $form->field($model, 'agendaIds')->label(FALSE)->checkboxList($data, [
        'unselect' => NULL,
        'separator' => '<br>',
    ]);
    ?>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
