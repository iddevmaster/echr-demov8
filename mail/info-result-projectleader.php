<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */
?>
<div style="text-align: center"><img src="<?= Url::to(Yii::$app->urlManager->baseUrl . '/images/logo.png', true) ?>" width="90"></div>
<div style="text-align: center; font-size: 18px"><?= Yii::$app->name ?></div>
<p>เรียน  <?= $submission->projectLeader->person->fullName ?></p>
<h4>เรื่อง แจ้งผลการพิจารณาจริยธรรมการวิจัยในมนุษย์</h4>
<table>
    <thead>
        <tr>
            <th colspan="2">โครงการ (<?= $submission->submissionType->name; ?>)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>เลขที่โครงการ</td>
            <td colspan="1"><?= $submission->project->project_code ?></td>
        </tr>
        <tr>
            <td>ภาษาไทย</td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>ภาษาอังกฤษ</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>

<p>
    เนื่องจากเจ้าหน้าที่ที่รับผิดชอบโครงการได้ทำการ upload <font style="color: red">"หนังสือ<?= \app\models\Submission::getResolutionLables()[$submission->resolution] ?>" </font>  ขึ้นไปยังระบบเป็นที่เรียบร้อยแล้ว จึงเรียนมาเพื่อโปรดทราบและท่านสามารถเข้าไปตรวจสอบได้ในระบบ
</p>

<?= Html::a('เข้าสู่ระบบเพื่อตรวจสอบข้อมูล', Url::to(['site/login'], TRUE)) ?>
<p>
    <font style="color: red"><?= $submission->contactLetter; ?></font>    
</p>

<br>
<hr color="red" align="center" width="70%" size="5">
<br>
<p>To <?= $submission->projectLeader->person->fullNameEng ?></p>
<h4>Subject: Research Protocol Evaluation Result Notification</h4>
<table>
    <thead>
        <tr>
            <th colspan="2">Research title: (<?= $submission->submissionType->name_eng; ?>)</th>
        </tr>
    </thead>
    <tbody>
                <tr>
            <td>HE</td>
            <td colspan="1"><?= $submission->project->project_code ?></td>
        </tr>
        <tr>
            <td>Thai</td>
            <td colspan="1"><?= $submission->project->name_thai ?></td>
        </tr>
        <tr>
            <td>English</td>
            <td colspan="1"><?= $submission->project->name_eng ?></td>
        </tr>
    </tbody>
</table>

<p>
    This is to notify you that the official endorsed result of your research study has been uploaded. You can now log in to view it.
</p>

<?= Html::a('Log in.', Url::to(['site/login'], TRUE)) ?>
<p>
    <font style="color: red"><?= $submission->contactLetterEng; ?></font>    
</p>