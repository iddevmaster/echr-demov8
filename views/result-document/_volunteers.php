<?php
$volunteers = app\models\SubmissionVolunteer::find()->submissionId($submission->id)->isDeleted(false)->all();
?>
<?php foreach ($volunteers as $volunteer) { ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; อาสาสมัครหมายเลข <?= $volunteer->volunteer->code; ?>, <?= \app\models\SubmissionVolunteer::typeLabels()[$volunteer->type]; ?> <?= isset($volunteer->follow_up_no) ? $volunteer->follow_up_no : ""; ?><br>
<?php } ?>
