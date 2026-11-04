<p style="text-align: center"><strong><?= Yii::t('app', 'รายงาน{0} ประจำสาขาวิชาคณะที่ {1}', [$model->title, $model->panel_id]) ?></strong></p>
<p style="text-align: center"><strong><?= Yii::t('app', 'ครั้งที่ {0}', [$model->yearNo]) ?></strong></p>
<p style="text-align: center"><strong><?= Yii::$app->formatter->asDate($model->start_date, 'php:l ') . Yii::t('app', 'ที่') . Yii::$app->formatter->asDate($model->start_date, ' dd MMMM') . ' พ.ศ. ' . Yii::$app->util->getBEYear(new DateTime()) ?></strong></p>
<p style="text-align: center"><strong><?= Yii::t('app', 'ณ {0}', [isset($model->meetingRoom) ? $model->meetingRoom->name : ""]) ?></strong></p>
<p><?= Yii::t('app', 'ผู้เข้าประชุม') ?></p>
<table style="width: 100%;border-style: solid; border-width: 1px; border-color: black;">
    <tbody>
        <?php
        $people = $model->getMeetingPeople()->isDeleted(FALSE)->all();
        foreach ($people as $i => $person):
            ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $person->person->fullName ?></td>
                <td><?= $person->role_name ?></td>
            </tr>

        <?php endforeach; ?>
    </tbody>
</table>
<p><strong><?= Yii::t('app', 'องค์ประชุมครบ') ?></strong></p>
<p>(
    <?php
    $categories = app\models\JobCategory::find()->isDeleted(FALSE)->all();
    $catCounts = [];
    foreach ($categories as $cat) {
        $catCounts[$cat->name] = $model->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->jobCategory($cat->id)->count();
    }
    $catCounts[Yii::t('app', 'บุคคลภายนอก')] = $model->getMeetingPeople()->joinWith(['person'])->isDeleted(FALSE)->isExternal()->count();
    foreach ($catCounts as $key => $val):
        ?>
        <?= $key ?> <?= $val ?> <?= Yii::t('app', 'ท่าน') ?>
    <?php endforeach; ?>
    <?= Yii::t('app', 'และจำนวนกรรมการที่เข้าร่วมประชุมรวมมากกว่า 8 คน') ?>)
</p>

<p><strong><?= Yii::t('app', 'เปิดประชุม {0} นาฬิกา', [Yii::$app->formatter->asTime($model->start_time)]) ?></strong></p>
<?php
$mas = $model->getMeetingAgendas()->isDeleted(FALSE)->parentAgenda(NULL)->orderBy('sort')->all();
foreach ($mas as $ma):
    ?>
    <p><strong><u><?= Yii::t('app', 'วาระที่ {0}', [$ma->sort_label]) ?></u></strong>&nbsp;<span><?= $ma->title ?></span></p>
    <?php if (isset($ma->description)): ?>
        <span><?= $ma->description ?></span>
    <?php endif; ?>
    <?php if (isset($ma->resolution)): ?>
        <span><?= $ma->resolution ?></span>
    <?php endif; ?>
    <?php if (isset($ma->conclusion)): ?>
        <span><?= $ma->conclusion ?></span>
    <?php endif; ?>
    <?php if (isset($ma->summary)): ?>
        <span><?= $ma->summary ?></span>
    <?php endif; ?>
    <?php
    $secMas = $ma->getMeetingAgendas()->isDeleted(FALSE)->orderBy('sort ASC')->all();
    if (count($secMas) > 0):
        if ($ma->agenda->is_submission) {
            echo $this->renderFile('@app/views/meeting/_submission-agenda.php', ['mas' => $secMas]);
        } else {
            echo $this->renderFile('@app/views/meeting/_normal-agenda.php', ['mas' => $secMas]);
        }
    endif;
    ?>
<?php endforeach; ?>
<p><strong><?= Yii::t('app', 'ปิดประชุม {0} นาฬิกา', [Yii::$app->formatter->asTime($model->end_time)]) ?></strong></p>
<span></span>
<?php $chairman = $model->getChairman(); ?>
<table style="width: 100%;">
    <tbody>
        <tr>
            <td style="text-align: center;">
                <span>(................................)</span>
                <span><?= Yii::t('app', 'ผู้บันทึกรายงานการประชุม') ?></span>
            </td>
            <td style="text-align: center;">
                <span>(................................)</span>
                <span><?= Yii::t('app', 'กรรมการและเลขานุการ (ผู้ตรวจรายงานการประชุม)') ?></span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <span></span>
                <span>(<?= isset($chairman) ? $chairman->person->fullName : "................................" ?>)</span>
                <span><?= Yii::t('app', 'ประธานคณะกรรมการจริยธรรมการวิจัยในมนุษย์ มหาวิทยาลัยขอนแก่น') ?></span>
            </td>
        </tr>
    </tbody>
</table>
<span></span>
<?php
$mas = $model->getMeetingAgendas()->joinWith(['agenda'])->isDeleted(FALSE)->isSubmission()->parentAgenda(NULL)->orderBy('sort')->all();
foreach ($mas as $ma):
    $secMas = $ma->getMeetingAgendas()->isDeleted(FALSE)->orderBy('sort ASC')->all();
    foreach ($secMas as $secMa):
        $thMas = $secMa->getMeetingAgendas()->isDeleted(FALSE)->orderBy('sort ASC')->all();
        if (count($thMas) == 0) {
            continue;
        }
        ?>
        <table style="width: 100%;border-style: solid; border-width: 1px; border-color: black;background-color: #d9d9d9;">
            <tbody>
                <tr>
                    <td style="text-align: center">
                        <span><?= Yii::t('app', 'โครงการที่ขอรับการพิจารณาจริยธรรมการวิจัยในมนุษย์') ?></span>
                        <span><?= Yii::t('app', 'วาระการประชุมครั้งที่ {0} วาระที่ {1}', [$model->yearNo, $secMa->sort_label]) ?></span>
                        <span><?= Yii::t('app', '"{0}" จำนวน {1} โครงการ', [$secMa->title, count($thMas)]) ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <span></span>
        <?php foreach ($thMas as $thMa): ?>
            <table style="width: 100%;">
                <tbody>
                    <tr>
                        <td style="background-color: #d9d9d9;border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'วาระที่ {0}', [$thMa->sort_label]) ?></td>
                        <td style="background-color: #d9d9d9;border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'เลขที่โครงการ {0}', [$thMa->project->project_code]) ?> <?= $thMa->submission->project->is_child_project ? " (" . Yii::t('app', 'เด็ก') . ")" : "" ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาไทย)') ?> <?= $thMa->project->name_thai ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'ชื่อโครงการ (ภาษาอังกฤษ)') ?> <?= $thMa->project->name_eng ?></td>
                    </tr>
                    <tr>
                        <td style="border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'ผู้วิจัย'); ?> <?= $thMa->submission->project->projectLeader->person->fullName ?></td>
                        <td style="border-style: solid; border-width: 1px; border-color: black;"><?= Yii::t('app', 'สังกัด'); ?> <?= $thMa->submission->project->projectLeader->person->fullOrg ?></td>
                    </tr>
                    <?php
                    $people = $thMa->coiPeople;
                    if (count($people) > 0):
                        ?>
                        <tr>
                            <td colspan="2">COI <?php
                                $names = ArrayHelper::getColumn($people, 'fullName');
                                echo implode(', ', $names);
                                ?>
                            </td>
                        </tr>
                        <?php
                    endif;
                    ?>
                    <tr>
                        <td colspan="2" style="border-style: solid; border-width: 1px; border-color: black;"><?= $thMa->description ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-style: solid; border-width: 1px; border-color: black;">
                            <span><?= Yii::t('app', 'มติที่ประชุม') ?> <?= $thMa->submission->resolutionLabel ?></span>
                            <span><?= $thMa->conclusion ?></span>
                            <span><?= $thMa->summary ?></span>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        <?php endforeach; ?>
        <span></span>
    <?php endforeach; ?>
<?php endforeach; ?>
