<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$saeVols = $project->getSaeVolunteers();
$volsByCode = ArrayHelper::index($saeVols, null, 'volunteer.code');
?>

<table class="table table-bordered table-condensed table-striped">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th><?= Yii::t('app', 'เลขที่อาสาสมัคร') ?></th>
            <th><?= Yii::t('app', 'ประเภทการติดตาม') ?></th>
            <th><?= Yii::t('app', 'วันที่ยื่นเอกสาร') ?></th>
            <th><?= Yii::t('app', 'ไฟล์เอกสาร') ?></th>
            <th><?= Yii::t('app', 'การประชุม') ?></th>
            <th><?= Yii::t('app', 'เสียชีวิตหรือไม่') ?></th>
            <th><?= Yii::t('app', 'ผลประเมิน') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        
        foreach ($volsByCode as $code => $vols):
            $volsByType = ArrayHelper::index($vols, null, 'submissionVolunteer.id');
            $totalVols = count($vols);
            $count = 0;
            $i++;
            foreach ($volsByType as $typeVols):
                $typeCount = 0;
                $totalTypeVols = count($typeVols);
                foreach ($typeVols as $saeVol):
                    $count++;
                    
                    $typeCount++;
                    ?>
                    <tr>
                        <?php if ($count == 1): ?>
                            <td class="text-center" rowspan="<?= $totalVols ?>"><?= $i; ?></td>
                            <td rowspan="<?= $totalVols ?>"><?= $saeVol->volunteer->code ?></td>
                        <?php endif; ?>
                        <?php if ($typeCount == 1): ?>
                            <td rowspan="<?= $totalTypeVols; ?>"><?= $saeVol->submissionVolunteer->typeLabel ?></td>
                            <td rowspan="<?= $totalTypeVols; ?>"><?= Yii::$app->formatter->asDate($saeVol->submission->submittedAt) ?></td>
                            <td rowspan="<?= $totalTypeVols; ?>"><?= isset($saeVol->submissionVolunteer->submissionDocument) ? $saeVol->submissionVolunteer->submissionDocument->fileIconHtml : '' ?></td>
                            <td rowspan="<?= $totalTypeVols; ?>"><?= $saeVol->submissionVolunteer->agendaTitle ?></td>
                        <?php endif; ?>
                            <td class="text-center"><?= $saeVol->dead ? Yii::$app->util->booleanIconFormat($saeVol->dead) : "" ?></td>
                        <td><?php
                            if (Yii::$app->util->checkPermission('sae-volunteer.create')) {
                                echo Html::a($saeVol->submissionCommittee->person->fullName,
                                    ['sae-volunteer/create', 'submissionVolunteerId' => $saeVol->submissionVolunteer->id
                                    , 'sCommitteeId' => $saeVol->submission_committee_id], ['role' => 'modal-remote']);
                            } else {
                                echo "";
                            }
                                 ?></td>
                    </tr>
                    <?php
                endforeach;
            endforeach;
        endforeach;
        ?>
    </tbody>
</table>