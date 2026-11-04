<?php $reTeam = app\models\ProjectResearcher::find()->submission($submission->id)->isDeleted(false)->isLeader(FALSE)->one(); ?>
<?php if(isset($reTeam)){ ?>

<p><strong><?= isset($eng) ? "Approved Investigators" : "ทีมวิจัย"; ?></strong></p>
<table cellspacing="0" class="Table" style="border-collapse:collapse; border:none; width:100%">
    <thead>
        <tr>
            <td style="background-color:#d0cece; width:5%">
            </td>
            <td style="background-color:#d0cece; width:32.5%">
               <strong><?= isset($eng) ? "Name" : "รายชื่อ"; ?></strong>
            </td>
            <td style="background-color:#d0cece; width:62.5%">
               <strong><?= isset($eng) ? "Affiliation" : "สังกัด"; ?></strong>
            </td>
        </tr>
        <?php
        $i = 1;

        foreach ($researchers as $researcher) {
           
        ?>
        <tr>
            <td>
               <?= $i++; ?>
            </td>
            <td>
                <?= isset($eng) ? $researcher->person->fullNameEngNoTitle : $researcher->person->fullNameNoTitle ?>
            </td>
            <td>
               <?= isset($eng) ? $researcher->person->divisionEng : $researcher->person->divisionThai ?>
            </td>
        </tr>
        <?php } ?>
    </thead>
</table>
<?php } ?>