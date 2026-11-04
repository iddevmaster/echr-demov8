<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$docs = $project->getLatestEndorseDocuments();
//\yii\helpers\VarDumper::dump(yii\helpers\ArrayHelper::getColumn($docs, 'id'));
//\yii\helpers\VarDumper::dump(yii\helpers\ArrayHelper::getColumn($docs, 'document_id'));
Pjax::begin(['id' => 'project-document-history-pjax']);
?>

<table class="table table-bordered table-condensed table-striped">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th><?= Yii::t('app', 'ชื่อเอกสาร') ?></th>
            <th><?= Yii::t('app', 'เวอร์ชันเอกสาร') ?></th>
            <th><?= Yii::t('app', 'วันที่เวอร์ชันเอกสาร') ?></th>
            <th><?= Yii::t('app', 'ไฟล์เอกสาร') ?></th>
            <th><?= Yii::t('app', 'ประเภทการยื่นโครงการ') ?></th>
            <?php if (Yii::$app->util->checkPermission('submission-document.group-document')): ?>
                <th></th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($docs as $doc):
            $i++;
            $subDocs = $doc->getEndorseHistories();
            ?>
            <tr>
                <td rowspan="<?= count($subDocs) + 1 ?>" class="text-center"><?= $i ?></td>
                <td rowspan="<?= count($subDocs) + 1 ?>"><?= $doc->name ?></td>
                <td><?= $doc->version ?></td>
                <td><?= Yii::$app->formatter->asDate($doc->version_at) ?></td>
                <td><?= $doc->getFileIconHtmlHistory(); ?></td>
                <td><?= $doc->submission->submissionTypeName ?></td>
                <?php if (Yii::$app->util->checkPermission('submission-document.group-document')): ?>
                    <td><?=
                        Html::a('<i class="icon md-edit"></i>', ['submission-document/group-document', 'id' => $doc->id], [
                            'class' => 'btn btn-flat', 'role' => 'modal-remote'
                        ]);
                        ?></td>
                <?php endif; ?>
            </tr>
            <?php
            $j = 0;
            foreach ($subDocs as $doc):
                $j++;
                ?>
                <tr>
                    <td><?= $doc->version ?></td>
                    <td><?= Yii::$app->formatter->asDate($doc->version_at) ?></td>
                    <td><?= $doc->getFileIconHtmlHistory(); ?></td>
                    <td><?= $doc->submission->submissionTypeName ?></td>
                    <?php if (Yii::$app->util->checkPermission('submission-document.group-document')): ?>
                        <td><?=
                            Html::a('<i class="icon md-edit"></i>', ['submission-document/group-document', 'id' => $doc->id], [
                                'class' => 'btn btn-flat', 'role' => 'modal-remote'
                            ]);
                            ?></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<?php
Pjax::end();
?>