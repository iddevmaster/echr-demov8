<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model app\models\QuestionnaireTitle */
$this->title = Yii::t('app', 'แก้ไขและเพิ่มตัวเลือกแบบสอบถาม');
$this->params['breadcrumbs'][] = ['label' => 'หัวข้อแบบสอบถาม', 'url' => ['questionnaire-title/index','id'=>$model->submission_type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meeting-update">
    <div class="panel">
        <div class="panel-body">
            <?php
            $prContent = $this->renderFile('@app/views/questionnaire-choice/index.php', [
                'searchModel' => $prSearch,
                'dataProvider' => $prProvider,
                'questionTitle'=>$model,
                
            ]);
           echo Tabs::widget([
                'itemOptions' => [
                    'class' => 'padding-top-15'
                ],
                'items' => [
                    [
                        'label' => 'ข้อมูลหัวข้อแบบสอบถาม',
                        'content' => $this->render('_form', [
                            'model' => $model,
                        ]),
                        'active' => true
                    ],
                    [
                        'label' => 'เพิ่มตัวเลือกแบบสอบถาม',
                        'content' => $prContent,
                    ]                
                    
                ]
            ]);
            ?>
        </div>
    </div>


</div>
