<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $acknowledgeSubmissions app\models\Submission[] */
/* @var $endorseSubmissions app\models\Submission[] */
/* @var $rejectedSubmissions app\models\Submission[] */
/* @var $totalCount int */

//$this->title = Yii::t('app', 'เอกสารรออนุมัติ');
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="president-approve-result-documents">

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-8">
            <h3 style="margin-top:0;">
                <?= Yii::t('app', 'เอกสารแจ้งผลรอผุ้ประสานงานตรวจสอบความถูกต้อง') ?> <?= Yii::t('app', 'รายการทั้งหมด') ?> <?= $totalCount ?> <?= Yii::t('app', 'รายการ') ?>
            </h3>
        </div>
        <div class="col-md-4 text-right">
            <span class="text-muted">ค่าเริ่มต้น (Default):</span><br>
            <button type="button" id="btn-approve-all" class="btn btn-success btn-raised">
                <i class="icon md-check-all"></i> <?= Yii::t('app', 'อนุมัติทั้งหมด (Approve All)') ?>
            </button>
        </div>
    </div>

    <?php
    $form = ActiveForm::begin([
                'id' => 'president-bulk-approve-form',
                'action' => Url::to(['submission/president-approve-result-documents']),
                'method' => 'post',
    ]);
    ?>

    <?php // ============ TABLE 1: หนังสือแจ้งผลการพิจารณา (Acknowledge / รับทราบ) ============  ?>
    <div class="panel panel-info" style="border-left: 4px solid #5bc0de;">
        <div class="panel-heading">
            <h4 class="panel-title" style="margin:0;">
                <i class="icon md-file-text"></i>
                <?= Yii::t('app', 'หนังสือแจ้งผลการพิจารณา') ?>
                <span class="badge badge-info"><?= count($acknowledgeSubmissions) ?> รายการ</span>
            </h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <?php if (count($acknowledgeSubmissions) > 0): ?>
                <table class="table table-striped table-condensed table-hover" id="table-acknowledge" style="margin-bottom:0;">
                    <thead>
                        <tr style="background-color: #f5f5f5;">
                            <th style="width:15%"><?= Yii::t('app', 'CODE') ?></th>
                            <th style="width:30%"><?= Yii::t('app', 'ประเภทการยื่นรายงาน') ?></th>
                            <th style="width:10%"><?= Yii::t('app', 'ผลการพิจารณา') ?></th>
                            <th style="width:10%; text-align:center;"><?= Yii::t('app', 'รายละเอียดโครงการ') ?><br><?= Yii::t('app', 'คลิก ที่ Icon ') ?><i class="icon wb-eye"></i></th>
                            <th style="width:10%; text-align:center;"><?= Yii::t('app', 'หนังสือแจ้งผล') ?><br><?= Yii::t('app', 'คลิก ที่ Icon ') ?><i class="icon md-chevron-down"></i></th>
                            <th style="width:25%; text-align:center;"><?= Yii::t('app', 'เลือกเพื่ออนุมัติ') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($acknowledgeSubmissions as $index => $submission): ?>
                            <?=
                            $this->render('_cra-bulk-row', [
                                'submission' => $submission,
                                'index' => $index,
                            ])
                            ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted" style="padding: 15px;"><?= Yii::t('app', 'ไม่มีเอกสารรออนุมัติ') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php // ============ TABLE 2: หนังสือรับรองโครงการ (Endorse / รับรอง) ============  ?>
    <div class="panel panel-warning" style="border-left: 4px solid #f0ad4e;">
        <div class="panel-heading">
            <h4 class="panel-title" style="margin:0;">
                <i class="icon md-star"></i>
                <?= Yii::t('app', 'หนังสือรับรองโครงการ') ?>
                <span class="badge badge-warning"><?= count($endorseSubmissions) ?> รายการ</span>
            </h4>
        </div>
        <div class="panel-body" style="padding:0;">
            <?php if (count($endorseSubmissions) > 0): ?>
                <table class="table table-striped table-condensed table-hover" id="table-endorse" style="margin-bottom:0;">
                    <thead>
                        <tr style="background-color: #f5f5f5;">
                            <th style="width:15%"><?= Yii::t('app', 'CODE') ?></th>
                            <th style="width:30%"><?= Yii::t('app', 'ประเภทการยื่นรายงาน') ?></th>
                            <th style="width:10%"><?= Yii::t('app', 'ผลการพิจารณา') ?></th>
                            <th style="width:10%; text-align:center;"><?= Yii::t('app', 'รายละเอียดโครงการ') ?><br><?= Yii::t('app', 'คลิก ที่ Icon ') ?><i class="icon wb-eye"></i></th>
                            <th style="width:10%; text-align:center;"><?= Yii::t('app', 'หนังสือแจ้งผล') ?><br><?= Yii::t('app', 'คลิก ที่ Icon ') ?><i class="icon md-chevron-down"></i></th>
                            <th style="width:25%; text-align:center;"><?= Yii::t('app', 'เลือกเพื่ออนุมัติ') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($endorseSubmissions as $index => $submission): ?>
                            <?=
                            $this->render('_cra-bulk-row', [
                                'submission' => $submission,
                                'index' => $index,
                            ])
                            ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted" style="padding: 15px;"><?= Yii::t('app', 'ไม่มีเอกสารรออนุมัติ') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <?php // ============ TABLE 3: เอกสารที่ส่งคืนแก้ไข (Read-only) ============ ?>
    <?php if (count($rejectedSubmissions) > 0): ?>
        <div class="panel panel-default" style="border-left: 4px solid #d9534f; background-color: #fff8f0;">
            <div class="panel-heading" style="background-color: #fff3e0;">
                <h4 class="panel-title" style="margin:0; color: #d9534f;">
                    <i class="icon md-undo"></i>
                    <?= Yii::t('app', 'เอกสารที่ส่งคืนแก้ไข (รอเจ้าหน้าที่)') ?>
                </h4>
                <small class="text-muted"><?= Yii::t('app', 'รายการเหล่านี้ท่านได้สั่งแก้ไขแล้ว และอยู่ระหว่างดำเนินการโดยเจ้าหน้าที่') ?></small>
            </div>
            <div class="panel-body" style="padding:0;">
                <table class="table table-condensed" style="margin-bottom:0;">
                    <thead>
                        <tr style="background-color: #fff3e0;">
                            <th style="width:10%"><?= Yii::t('app', 'CODE') ?></th>
                            <th style="width:15%"><?= Yii::t('app', 'TYPE') ?></th>
                            <th style="width:15%"><?= Yii::t('app', 'ผลการพิจารณา') ?></th>
                            <th style="width:8%; text-align:center;"><?= Yii::t('app', 'OLD DOC') ?></th>
                            <th><?= Yii::t('app', 'COMMENT (สิ่งที่สั่งแก้)') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rejectedSubmissions as $rejected): ?>
                            <tr>
                                <td><?= Html::encode($rejected->project->project_code) ?></td>
                                <td><?= Html::encode($rejected->submissionTypeName) ?></td>
                                <td><?= Html::encode($rejected->resolution) ?></td>
                                <td style="text-align:center;">
                                    <?=
                                    Html::a(
                                            '<i class="icon wb-eye"></i>',
                                            ['submission/project-submission', 'submissionId' => $rejected->id],
                                            ['target' => '_blank', 'data-pjax' => 0]
                                    )
                                    ?>
                                </td>
                                <td>
                                    <i class="icon md-comment-text" style="color: #d9534f;"></i>
                                    "<?= Html::encode($rejected->president_comment) ?>"
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <?php // ============ Sticky Footer ============  ?>
    <div id="sticky-footer" style="position:fixed; bottom:0; left:0; right:0; background:#fff; border-top:2px solid #ccc; padding:15px 30px; z-index:1000; box-shadow: 0 -2px 5px rgba(0,0,0,0.1);">
        <div class="row">
            <div class="col-md-6" style="padding-top: 8px;">
                <span id="decision-summary" style="font-size: 14px;">
                    เลือกแล้ว: <strong id="count-approve" style="color:#5cb85c;">0</strong> อนุมัติ |
                    <strong id="count-reject" style="color:#d9534f;">0</strong> ไม่อนุมัติ
                </span>
            </div>
            <div class="col-md-6 text-right">
                <a href="<?= Url::to(['site/index']) ?>" class="btn btn-default btn-raised btn-lg" style="margin-right: 10px;">
                    <?= Yii::t('app', 'ยกเลิก') ?>
                </a>
                <?=
                Html::submitButton(
                        '<i class="icon md-check"></i> ' . Yii::t('app', 'ยืนยันผลการพิจารณา'),
                        [
                            'class' => 'btn btn-success btn-raised btn-lg',
                            'id' => 'btn-submit-decisions',
                        ]
                )
                ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php // Bottom padding for sticky footer ?>
<div style="height: 80px;"></div>

<?php
$js = <<<'JS'

// 1. Toggle detail row expand/collapse
$(document).on('click', '.btn-toggle-detail', function(e) {
    e.preventDefault();
    var target = $(this).data('target');
    var row = $('#' + target);
    var icon = $(this).find('i');
    if (row.css('display') === 'none') {
        row.css('display', 'table-row');
        icon.removeClass('md-chevron-down').addClass('md-chevron-up');
    } else {
        row.css('display', 'none');
        icon.removeClass('md-chevron-up').addClass('md-chevron-down');
    }
});

// 2. Show/hide comment row when reject is selected
$(document).on('change', '.decision-radio', function() {
    var sid = $(this).data('sid');
    var commentRow = $('#comment-' + sid);
    if ($(this).val() === 'reject') {
        commentRow.css('display', 'table-row');
        commentRow.find('textarea').focus();
    } else {
        commentRow.css('display', 'none');
        commentRow.find('textarea').val('');
    }
    updateSummary();
});

// 3. "Approve All" button
$('#btn-approve-all').on('click', function() {
    $('input.decision-radio[value="approve"]').prop('checked', true);
    // Hide all comment rows
    $('.comment-row').css('display', 'none');
    $('.president_comment-input').val('');
    updateSummary();
});

// 4. Summary counter
function updateSummary() {
    var approveCount = $('input.decision-radio[value="approve"]:checked').length;
    var rejectCount = $('input.decision-radio[value="reject"]:checked').length;
    $('#count-approve').text(approveCount);
    $('#count-reject').text(rejectCount);
}

// 5. Form validation before submit
$('#president-bulk-approve-form').on('beforeSubmit', function(e) {
    var hasDecision = false;
    var valid = true;
    var firstError = null;

    $('input.decision-radio:checked').each(function() {
        var sid = $(this).data('sid');
        var action = $(this).val();

        if (action !== 'pending') {
            hasDecision = true;
        }

        if (action === 'reject') {
            var comment = $('#president_comment_' + sid).val().trim();
            if (comment === '') {
                valid = false;
                $('#president_comment_' + sid).css('border-color', 'red');
                if (!firstError) {
                    firstError = $('#president_comment_' + sid);
                }
            } else {
                $('#president_comment_' + sid).css('border-color', '');
            }
        }
    });

    if (!hasDecision) {
        alert('กรุณาเลือกอย่างน้อย 1 รายการ');
        return false;
    }

    if (!valid) {
        alert('กรุณาระบุเหตุผลในการส่งคืนแก้ไขสำหรับรายการที่ไม่อนุมัติ');
        if (firstError) {
            firstError.focus();
        }
        return false;
    }

    var approveCount = $('input.decision-radio[value="approve"]:checked').length;
    var rejectCount = $('input.decision-radio[value="reject"]:checked').length;

    return confirm('ยืนยันผลการพิจารณา?\n\nอนุมัติ: ' + approveCount + ' รายการ\nส่งคืนแก้ไข: ' + rejectCount + ' รายการ');
});

// Initial summary
updateSummary();

JS;

$this->registerJs($js);
?>
