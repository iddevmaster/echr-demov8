<?php
use yii\bootstrap\Collapse;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$mas = $meeting->getMeetingAgendas()->isDeleted(FALSE)->hasSubmission()->notCOI()->orderBy('sort_label')->all();
$items = [];
foreach ($mas as $ma) {
    $items[] = [
        'label' => $ma->fullTitle,
        'content' => $this->renderFile('@app/views/meeting/_submission-files.php', [
            'submission' => $ma->submission
        ]),
//        'options' => [
//            'class' => 'panel panel-primary'
//        ],
    ];
            $ardProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $ma->submission->getResultDocuments()
        ]);
        echo $this->renderFile('@app/views/submission/letter-result.php', [
            'submission' => $ma->submission,
            'pjaxId' => 'result-'.$ma->submission_id,
            'dataProvider' => $ardProvider,
        ]);
}

echo Collapse::widget([
    'items' => $items
]);