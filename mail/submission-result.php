<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
$lc = Yii::$app->formatter->locale;
Yii::$app->formatter->locale = 'th';
if (isset($ma)) {
    $meetingDate = Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:d F ') . Yii::$app->formatter->asDate($ma->meeting->start_date, 'php:Y');
} 

?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน <?= $submission->projectLeader->person->fullName ?></p>
<p>เรื่อง <?= \Yii::t('app', 'ผลการประเมินด้านจริยธรรมการวิจัยในมนุษย์ภายหลังการประชุมครั้งที่ {0}', [isset($ma) ? $ma->meeting->yearNo : ""]) ?></p>
<p>
    ตามที่ท่านเสนอโครงการวิจัยเพื่อขอรับการพิจารณาด้านจริยธรรมการวิจัยในมนุษย์ เรื่อง “<?= $submission->project->name_thai ?>” (<?= $submission->project->name_eng ?>) เลขที่โครงการ <?= $submission->project->project_code ?> นั้น 
    <?php if (isset($ma)): ?>
    ในการประชุมครั้งที่ <?= $ma->meeting->yearNo ?> วันที่ <?= $meetingDate ?> วาระ <?= $ma->fullTitle ?> 
    <?php endif; ?>
    ที่ประชุมมีมติ <?= $submission->resolutionLabel ?>
จึงเรียนมาเพื่อโปรดทราบเบื้องต้น ทั้งนี้หนังสือแจ้งผลฉบับลงนาม สำนักงานฯ จะขอส่งตามระบบต่อไป
</p>
<p>
<font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>

<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= $submission->projectLeader->person->fullNameEng ?></p>
<p>Subject <?= \Yii::t('app', 'Ethics in Human Research Evaluation Results after the board meeting no : {0}', [isset($ma) ? $ma->meeting->yearNo : ""]) ?></p>
<p>
    Research entitled “<?= $submission->project->name_thai ?>” (<?= $submission->project->name_eng ?>) Protocol no.: HE <?= $submission->project->project_code ?>  
    <?php if (isset($ma)): ?>
    Board meeting no <?= $ma->meeting->yearNo ?> Date <?= $meetingDate ?> / <?= $ma->fullTitle ?> 
    <?php endif; ?>
    Results <?= $submission->resolutionLabel ?>
This is an initial notification. The KKUEC office will send the endorsed result notification to you online.
</p>
<p>
<font style="color: red"><?= $submission->contactLetterEng; ?></font>    
</p>
<?php
Yii::$app->formatter->locale = $lc;
?>