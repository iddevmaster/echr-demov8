<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $pr->person->fullNameWithEng ?></p>
<p>เรื่อง แจ้งเตือนยังไม่ส่งโครงการ</p>
<p>
    โครงการ <?= $pr->project->name_thai ?>  <?= isset($pr->submission->submission_type_id) ? $pr->submission->submissionType->name : ""; ?>  ที่ท่านได้เสนอขอรับการพิจารณาจริยธรรมฯ
    <?php if (count($pendingCoResearchers) > 0): ?>
        ยังไม่ส่งถึงศูนย์จริยธรรมฯ เนื่องจากผู้ร่วมวิจัยยังตอบรับไม่ครบถ้วนได้แก่ 
    <ul>
        <?php foreach ($pendingCoResearchers as $r): ?>
            <?php $eq = $r->getAcknowledgeEmailQueue(); ?>
            <li><?= $r->person->fullNameWithEng ?> (ส่งเมลล์เมื่อวันที่ <?= isset($eq) ? Yii::$app->formatter->asDate($eq->mail_at) : "-" ?>)</li>
        <?php endforeach; ?>
    </ul>
    ขอให้ท่านดำเนินการแจ้งผู้ร่วมตอบรับในอีเมลล์ที่ส่งไป
<?php else: ?>
    <?php if ($pr->submission->submissionType->submission_type_group_id == app\models\SubmissionTypeGroup::GROUP_NEW) { ?>
        มีผู้ร่วมวิจัยตอบรับครบถ้วนแล้ว แต่ยังไม่ส่งถึงศูนย์จริยธรรมฯ
    <?php } else { ?>
        แต่ยังไม่ส่งถึงศูนย์จริยธรรมฯ
    <?php } ?>
<?php endif; ?>
</p>

<p>
    <font style="color: red">หมายเหตุ : เป็น​ความอัตโนมัติ​ ส่งจากระบบหากต้องการติดต่อเจ้าหน้าที่</font>    
</p>
<hr color="red" align="center" width="70%" size="5">
<p>
    <font style="color: red">Note: This document is automatically generated. If you want to contact us</font>    
</p>