<div class="row">
    <div class="col-md-11 margin-left-50">
        <div class="panel panel-bordered panel-primary">
            <ul class="list-group list-group-bordered">
                <div class="row"><div class="col-md-3"><li class="list-group-item"><?= Yii::t('app', 'ชื่อโครงการ') ?> : </li></div><div class="col-md-9"><li class="list-group-item"><?= $submission->project->name_thai; ?> (<?= $submission->project->name_eng; ?>)</li></div></div>
                <div class="row"><div class="col-md-3"><li class="list-group-item"><?= Yii::t('app', 'หัวหน้าโครงการ') ?> : </li></div><div class="col-md-9"><li class="list-group-item"><?= $submission->project->projectLeader->person->fullNameWithEng; ?> <?= Yii::t('app', 'สังกัด') ?> : <?= $submission->project->projectLeader->person->fullOrg ?></li></div></div>
                <div class="row"><div class="col-md-3"><li class="list-group-item"><?= Yii::t('app', 'ข้อมูลที่ติดต่อหัวหน้าโครงการ') ?> : </li></div><div class="col-md-9"><li class="list-group-item"><?= Yii::t('app', 'เบอร์โทรศัพท์') ?> : <?= $submission->project->projectLeader->person->tel ?> <?= Yii::t('app', 'อีเมลล์') ?> : <?= $submission->project->projectLeader->person->email ?></li></div></div>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-11 margin-left-50">
        <div class="panel panel-bordered panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Yii::t('app', 'เอกสารโครงการ') ?></h3>
            </div>
            <ul class="list-group list-group-bordered">
                <?php foreach ($docs as $doc) : ?>
                    <li class="list-group-item"><?= yii\helpers\Html::a($doc->name, \yii\helpers\Url::to(['submission-document/download', 'id' => $doc->id]), ['target' => '_blank']) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <!-- End Example Panel With List Group -->
    </div>
</div>

