<?php
use kartik\widgets\Growl;

foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    echo Growl::widget([
        'type' => $key,
//            'title' => $message,
//            'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => $message,
        'showSeparator' => true,
        'delay' => false,
        'options' => [
            'class' => 'dark margin-10',
        ],
        'pluginOptions' => [
//            'showProgressbar' => true,
            'placement' => [
                'from' => 'top',
                'align' => 'center',
            ],
            'z_index' => 2000,
            'offset' => [
                'y' => 100,
            ],
            'delay' => 0,
        ]
    ]);
}
?>