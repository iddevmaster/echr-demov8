<?php

use yii\helpers\Html;
use yii\helpers\Url;

$meetingdate = isset($submission->meeting_plan_date) ? \Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->meeting_plan_date, 'php:Y') + 543) : "";
$senddate = isset($submission->send_plan_date) ? \Yii::$app->formatter->asDate($submission->send_plan_date, 'php:d ') . Yii::$app->util->thaiMonths[\Yii::$app->formatter->asDate($submission->send_plan_date, 'php:n')] . ' ' . (\Yii::$app->formatter->asDate($submission->send_plan_date, 'php:Y') + 543) : "";

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$title = "";
if (isset($submission->refSubmission)) {
    if ($submission->refSubmission->resolution == "C") {
        $committeeRevise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
        if (isset($committeeRevise) && $committeeRevise->resolution == 'C') {
            $title = \Yii::t('app', 'เรื่อง แจ้งขอความอนุเคราะห์ประเมินโครงการแก้ไขมติ C– ({0}) เพิ่มเติมบางประเด็น  ', [$submission->project->project_code]);
        } else {
            $title = \Yii::t('app', 'เรื่อง แจ้งขอความอนุเคราะห์ประเมินโครงการแก้ไขมติ C– {0} ', [$submission->project->project_code]);
        }
    } else {
        $title = \Yii::t('app', 'เรื่อง แจ้งขอความอนุเคราะห์ประเมินโครงการแก้ไขมติ R– ({0}) ', [$submission->project->project_code]);
    }
} else {
    if (isset($committee->committeePosition)) {
        $title = \Yii::t('app', 'เรื่อง แจ้งขอความอนุเคราะห์เป็นกรรมการ {0} พิจารณาโครงการวิจัย ', [$committee->committeePosition->name]);
    } else {
        $title = \Yii::t('app', 'เรื่อง แจ้งขอความอนุเคราะห์เป็นกรรมการพิจารณาโครงการวิจัย ');
    }
}
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p><?= Yii::t('app', 'เรียน อาจารย์ {0} ที่เคารพ', [$committee->person->fullName]) ?></p>
<h4><?= $title ?></h4>


<?php
$content = "";
if (isset($submission->refSubmission)) {
    if ($submission->refSubmission->resolution == "C") {
        $committeeRevise = \app\models\SubmissionCommitteeRevise::find()->submission($submission->id)->isDeleted(FALSE)->orderBy('id DESC')->one();
                if (isset($committeeRevise) && $committeeRevise->resolution == 'C') {

            ?>
        <p style="text-indent: 50px;">ตามที่อาจารย์ได้ให้ความอนุเคราะห์ประเมินโครงการ  เรื่อง “ <?= $submission->project->name_thai; ?> (<?= $submission->project->name_eng; ?>) ” (<?= $submission->project->project_code ?>)  และได้ให้ข้อเสนอแนะให้นักวิจัยแก้ไขและเพิ่มเติมบางประเด็นนั้น  บัดนี้นักวิจัยได้ชี้แจงต่อข้อคำถามและแก้ไขโครงการกลับมาแล้ว   ศูนย์จริยธรรมฯ จึงใคร่ขอความกรุณาอาจารย์พิจารณาการโครงการวิจัยดังกล่าวอีกครั้ง และส่งผลการประเมินโครงการ ภายในวันที่  <?= $senddate; ?> </p>
        <?php } else { ?>
        <p style="text-indent: 50px;"> ตามที่โครงการ  เรื่อง “ <?= $submission->project->name_thai; ?> (<?= $submission->project->name_eng; ?>) ” (<?= $submission->project->project_code ?>)  ได้นำเข้าพิจารณาในการประชุมครั้งที่ <?= $submission->refSubmission->meetingAgenda->meeting->yearNo; ?> ที่ประชุมมี มติ รับรองหลังจากผู้วิจัยแก้ไขตามมติที่ประชุม (มติ C) นั้น  บัดนี้นักวิจัยได้ชี้แจงต่อข้อคำถามและแก้ไขโครงการกลับมาแล้ว เนื่องจากอาจารย์ได้รับมอบหมายเป็นตัวแทนคณะกรรมการในการพิจารณาฯ ศูนย์จริยธรรมฯ จึงใคร่ขอความกรุณาพิจารณาการโครงการวิจัยดังกล่าว และส่งผลการประเมินโครงการ ภายในวันที่  <?= $senddate; ?>   </p>
            <?php
        }
    } else {
        ?>
        <p style="text-indent: 50px;"> ตามที่อาจารย์ได้ให้ความอนุเคราะห์ประเมินโครงการ เรื่อง “ <?= $submission->project->name_thai; ?> (<?= $submission->project->name_eng; ?>) ” (<?= $submission->project->project_code ?>) และได้ให้ข้อเสนอแนะต่างๆ ไปแล้วนั้น  โครงการดังกล่าวได้นำเข้าพิจารณาในการประชุมครั้งที่ <?= $submission->refSubmission->meetingAgenda->meeting->yearNo; ?> ที่ประชุมมี มติให้ผู้วิจัยชี้แจงต่อข้อคำถามเพื่อนำมาพิจารณาในการประชุมอีกครั้ง (มติ R )  บัดนี้นักวิจัยได้ชี้แจงต่อข้อคำถามและแก้ไขโครงการกลับมาแล้ว   ศูนย์จริยธรรมฯ จึงใคร่ขอความกรุณาอาจารย์พิจารณาการโครงการวิจัยดังกล่าวอีกครั้ง และส่งผลการประเมินโครงการ ภายในวันที่  <?= $senddate; ?>  ทั้งนี้ เพื่อศูนย์จริยธรรมฯ จะได้นำเข้าที่ประชุมเพื่อพิจารณาอีกครั้ง ในวันที่ <?= $meetingdate; ?> </p>
  
    <?php
    }
} else {
    ?>
    <table>
        <tbody>
            <tr>
                <td colspan="4" style="text-indent: 50px;"> <?= Yii::t('app', 'ศูนย์จริยรรมการวิจัยในมนุษย์ ขอความอนุเคราะห์อาจารย์ประเมินโครงการวิจัยเพื่อขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์') ?>  <?= $submission->submissionType->name ?> <?= Yii::t('app', 'เลขที่') ?> <?= $submission->project->project_code; ?> เรื่อง “ <?= $submission->project->name_thai; ?> (<?= $submission->project->name_eng; ?>) ” โดยมี <?= isset($submission->projectLeader) ? $submission->projectLeader->person->fullNameWithEng : "" ?> เป็นหัวหน้าโครงการวิจัย</td>
            </tr>
            <tr>
                <td colspan="4" style="text-indent: 50px;">และขอความกรุณาอาจารย์ส่งผลการประเมินโครงการ ภายในวันที่ <?= $senddate; ?> ทั้งนี้ เพื่อนำเข้าที่ประชุมพิจารณาในวันที่ <?= $meetingdate; ?> ทั้งนี้ขออนุญาตโทรติดต่อเพื่อยืนยันการประชุมอีกครั้ง </td>
            </tr>
            <tr>
                <td colspan="4" style="text-indent: 50px;" ><?= Yii::t('app', 'เมื่ออาจารย์ได้รับเอกสารแล้ว หากพิจารณาเห็นว่า ') ?></td>
            </tr>

            <tr>
                <td colspan="4" style="text-indent: 100px;" ><?= Yii::t('app', '1.ไม่สามารถพิจารณาโครงการได้ทันตามเวลาที่กำหนด') ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-indent: 100px;" ><?= Yii::t('app', '2. ไม่สามารถเข้าร่วมการพิจารณาในวันประชุมได้') ?></td>
            </tr>
            <tr>
                <td colspan="4" style="text-indent: 50px;"><?= Yii::t('app', 'รบกวนตอบกลับทาง ศูนย์ฯ ทันทีเพื่อให้ทางเลขานุการสามารถมอบหมายกรรมการท่านใหม่ต่อไปค่ะ') ?></td>
            </tr>
        </tbody>
    </table>
<?php } ?>
        <table>
            <tbody>
                <tr>
                    <td colspan="4" style="text-indent: 50px;"><?= Html::a(Yii::t('app', 'ตอบรับการพิจารณาอ่านโครงการวิจัยกรุณาคลิกที่นี่'), Url::to(['site/login'], TRUE)) ?> </td>
                </tr>
                <tr>
                    <td colspan="4" style="text-indent: 50px;"><font style="color: red"><?= $submission->contactLetter; ?></font></td>
                </tr>
            </tbody>
        </table>    
<p><?= Yii::t('app', 'ด้วยความเคารพ') ?></p>



