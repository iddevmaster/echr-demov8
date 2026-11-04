<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\Person */
$this->title = Yii::t('app', 'การจัดการข้อมูลส่วนตัวของผู้ใช้งาน');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="person-update">
    <?php
    echo $this->renderFile('@app/views/widgets/_growl.php');

    ?>
    <div class="panel">
        <div class="panel-body">
            <?php
            //echo Html::errorSummary([$model]);

            $training = $this->renderFile('@app/views/person-training/index.php', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);

            echo Tabs::widget([
                'itemOptions' => [
                    'class' => 'padding-top-15'
                ],
                'items' => [
                        [
                        'label' => Yii::t('app', 'ข้อมูลส่วนตัว'),
                        'content' => $this->render('_form', [
                            'model' => $model,
                            'regForm' => $regForm,
                        ]),
                        'active' => true
                    ],

                        [
                        'label' => Yii::t('app', 'ข้อมูลการอบรม'),
                        'content' => $training,
                    ],
                ]
            ]);
            ?>
        </div>
    </div>


</div>

