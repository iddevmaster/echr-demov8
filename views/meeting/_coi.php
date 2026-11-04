<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$mps = $model->getMeetingPeople()->isDeleted(FALSE)->all();
$i = 1;
?>
<table class="table table-bordered table-condensed table-striped">
    <thead>
        <tr>
            <th><?= Yii::t('app', 'ที่') ?></th>
            <th><?= Yii::t('app', 'ชื่อกรรมการ') ?></th>
            <th><?= Yii::t('app', 'โครงการวิจัยเลขที่') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mps as $mp):
            $mas = $mp->person->getCOIMeetingAgendas($model->id);
            if (count($mas) == 0) {
                continue;
            }
            ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= $mp->person->fullName ?></td>
            <td>
                <?php foreach ($mas as $ma): ?>
                <?= $ma->fullTitle ?><br>
                <?php endforeach;?>
            </td>
        </tr>
            <?php
        endforeach;
        ?>
    </tbody>
</table>