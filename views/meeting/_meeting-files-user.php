<?php
use yii\bootstrap\Collapse;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$mas = $meeting->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission()->notCOIMeeting()->orderBy('sort_label')->all();
$items = [];
foreach ($mas as $ma) {
    $items[] = [
        'label' => $ma->fullTitle,
        'content' => $this->renderFile('@app/views/meeting/_submission-files-user.php', [
            'submission' => $ma->submission
        ]),
//        'options' => [
//            'class' => 'panel panel-primary'
//        ],
    ];
}

echo Collapse::widget([
    'items' => $items
]);