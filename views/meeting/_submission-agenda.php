<table style="width: 100%;">
    <tbody>
    <?php
//    $people = $model->getMeetingPeople()->isDeleted(FALSE)->all();
    foreach ($mas as $ma):
        $thMas = $ma->getMeetingAgendas()->isDeleted(FALSE)->orderBy('sort ASC')->all();
        ?>
        <tr>
            <td style="border-style: solid; border-width: 1px; border-color: black;"><?= $ma->fullTitle ?></td>
            <td style="border-style: solid; border-width: 1px; border-color: black;text-align: right;"><?= count($thMas); ?></td>
            <td style="border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'โครงการ'); ?></td>
        </tr>

    <?php endforeach; ?>
    </tbody>
</table>
