<?php
$submissionFolder1 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_1)->one();
$submissionFolder2 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_2)->one();
$submissionFolder3 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_3)->one();
$submissionFolder4 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_4)->one();
?>
<?php if (isset($submissionFolder1->id)) { ?>
    <p><strong><?= isset($eng) ? "Submission Form/Research protocol" : "แบบเสนอขอรับการพิจารณาฯ/โครงร่างการวิจัย"; ?></strong></p>
    <ol>
        <?php
        $documents1 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_1)->orderBy('position ASC')->all();
        $i = 1;
        foreach ($documents1 as $document1) :
            ?>
            <li><span style="color:black"><?= isset($eng) ? $document1->certificateNameEng : $document1->certificateName ?></span></li>
        <?php endforeach; ?>

    </ol>
<?php } ?>

<?php if (isset($submissionFolder2->id)) { ?>
    <p><strong><?= isset($eng) ? "Informed consent documents" : "เอกสารข้อมูลและขอความยินยอม"; ?></strong></p>
    <ol>
        <?php
        $documents2 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_2)->orderBy('position ASC')->all();
        $i = 1;
        foreach ($documents2 as $document2) :
            ?>
            <li><span style="color:black"><?= isset($eng) ? $document2->certificateNameEng : $document2->certificateName ?></span></li>
        <?php endforeach; ?>

    </ol>
<?php } ?>    
<?php if (isset($submissionFolder3->id)) { ?>
    <p><strong><?= isset($eng) ? "Research Tools" : "เครื่องมือที่ใช้ในการวิจัย"; ?></strong></p>
    <ol>
        <?php
        $documents3 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_3)->orderBy('position ASC')->all();
        $i = 1;
        foreach ($documents3 as $document3) :
            ?>
            <li><span style="color:black"><?= isset($eng) ? $document3->certificateNameEng : $document3->certificateName ?></span></li>
        <?php endforeach; ?>

    </ol>
<?php } ?>    
<?php if (isset($submissionFolder4->id)) { ?>
    <p><strong><?= isset($eng) ? "Other documents" : "เอกสารอื่นๆ"; ?></strong></p>
    <ol>
        <?php
        $documents4 = app\models\SubmissionDocument::find()->isDeleted(false)->submission($submission->id)->hasFile()->isCertificate(false)->groupDoc(\app\models\SubmissionDocument::GROUP_4)->orderBy('position ASC')->all();
        $i = 1;
        foreach ($documents4 as $document4) :
            ?>
            <li><span style="color:black"><?= isset($eng) ? $document4->certificateNameEng : $document4->certificateName ?></span></li>
        <?php endforeach; ?>

    </ol>
<?php } ?>  
