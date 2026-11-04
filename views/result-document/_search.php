<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>
<div class="result-document-search margin-bottom-10">
    <?php
    $form = ActiveForm::begin([
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
//                            'target' => '#crud-datatable-ticket-h-pjax'
//                    'class' => 'form-inline'
                ],
                'type' => ActiveForm::TYPE_INLINE,
    ]);
    ?>
    <?= $form->field($searchModel, 'name')->textInput([]); ?>



    <div class="form-group">
    <?= Html::submitButton(Yii::t('app', 'ค้นหา'), ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>
</div>