<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

?>

<table class="table table-condensed table-bordered table-striped">
    <thead>
        <tr>
            <th><?= Yii::t('app', 'ประเภทการส่งโครงการ') ?></th>
            <th><?= Yii::t('app', 'ช่วงเวลา') ?></th>
            <th><?= Yii::t('app', 'ค่าเฉลี่ย') ?></th>
            <th><?= Yii::t('app', 'ค่ากลาง') ?></th>
            <th><?= Yii::t('app', 'SD') ?></th>
            <th><?= Yii::t('app', 'มากสุด') ?></th>
            <th><?= Yii::t('app', 'ต่ำสุด') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $submissionType => $durTypes): 
            $count = 0;
            ?>
        <?php foreach ($durTypes as $durType => $data): ?>
        <tr>
            <?php if ($count == 0): ?>
            <td rowspan="<?= count($durTypes) ?>"><?= $submissionType ?></td>
            <?php endif; ?>
            <td class="padding-5"><?= $durType ?></td>
            <?php foreach ($data as $d): ?>
            <td class="text-right"><?= $d ?></td>
            <?php endforeach; ?>
            <?php $count++; ?>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>