<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\PersonTraining */
?>
<div class="person-training-view">

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name_thai_course',
            'name_eng_course',
                [
                'attribute' => 'training_type_id',
                'value' => isset($model->trainingType) ? $model->trainingType->name : '',
            ],
//            'start_date',
//            'end_date',
                [
                'attribute' => 'start_date',
                'format' => ['date', 'php:d/m/Y H:i:s'],
            ],
                            [
                'attribute' => 'end_date',
                'format' => ['date', 'php:d/m/Y H:i:s'],
            ],
                [
                'attribute' => 'expire_date',
                'format' => ['date', 'php:d/m/Y'],
            ],
                [
                'attribute' => 'file',
                'format' => 'raw',
                'value' => Html::a(Yii::t('app', "ไฟล์อบรม"), 
                    // [$model->fileUrl]
                    Url::to(['person-training/download', 'id' => $model->id])
                ),
            ],
//                [Html::a(Yii::t('app', 'Update'), [$model->fileUrl])],
        ],
    ])
    ?>

</div>
