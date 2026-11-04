<?php
use kartik\widgets\Alert;

foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
    echo Alert::widget([
        'type' => $key,
//            'title' => $message,
//            'icon' => 'glyphicon glyphicon-ok-sign',
        'body' => $message,
        'showSeparator' => true,
        'delay' => 0,
        'options' => [
            'class' => 'dark'
        ]
    ]);
}
?>