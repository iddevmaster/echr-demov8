<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

?>

<table class="table table-condensed table-striped">
    <thead >
        <tr>
            <th style="border: 1px solid black;"><?= Yii::t('app', 'ประเภทการส่งโครงการ') ?></th>
            <th style="border: 1px solid black;"><?= Yii::t('app', 'ช่วงเวลา') ?></th>
            <th style="border: 1px solid black;"><?= Yii::t('app', 'ค่าเฉลี่ย') ?></th>
            <th style="border: 1px solid black;"><?= Yii::t('app', 'ค่ากลาง') ?></th>
            <th style="border: 1px solid black;"> <?= Yii::t('app', 'SD') ?></th>
            <th style="border: 1px solid black;"><?= Yii::t('app', 'มากสุด') ?></th>
            <th style="border: 1px solid black;"><?= Yii::t('app', 'ต่ำสุด') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $submissionType => $durTypes): 
            $count = 0;
            ?>
        <?php foreach ($durTypes as $durType => $data): ?>
        <tr>
            <?php if ($count == 0): ?>
            <td style="border: 1px solid black;" rowspan="<?= count($durTypes) ?>"><?= $submissionType ?></td>
            <?php endif; ?>
            <td style="border: 1px solid black;" class="padding-5"><?= $durType ?></td>
            <?php foreach ($data as $d): ?>
            <td  style="border: 1px solid black;"class="text-right"><?= $d ?></td>
            <?php endforeach; ?>
            <?php $count++; ?>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>
    </tbody>
</table>