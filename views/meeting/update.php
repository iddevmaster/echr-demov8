<?php

use yii\helpers\Html;

\app\assets\HotkeysAsset::register($this);
/* @var $this yii\web\View */
/* @var $model app\models\Meeting */
$this->title = Yii::t('app', 'แก้ไขการประชุม');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'จัดการการประชุม'), 'url' => ['meeting/index']];
$this->params['breadcrumbs'][] = $this->title;
$currentRole = \Yii::$app->session->get('currentRole');
?>
<div class="meeting-update">

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="pull-right">
                <?php yii\widgets\Pjax::begin(['id' => 'meeting-toolbar-pjax']); ?>
                <?php if (isset($model->checked_status)) { ?>
                    <span class="label bg-grey-600 label-lg"><?= yii::t('app', 'สถานะ : ') . app\models\Meeting::getCheckedStatusLabels()[$model->checked_status]; ?></span>
                <?php } ?>
                <?php
                if (isset($model->start_time)) {
                    echo Html::tag('span', Yii::t('app', 'เปิด : ') . Yii::$app->formatter->asTime($model->start_time), ['class' => 'label label-success label-lg']);
                }
                ?>
                <?php
                if (isset($model->end_time)) {
                    echo Html::tag('span', Yii::t('app', 'ปิด : ') . Yii::$app->formatter->asTime($model->end_time), ['class' => 'label label-danger label-lg']);
                }
                ?>
                <?php if ($model->hasChild) : ?>
                    <?= Html::button('<i class="icon md-face"></i> ' . Yii::t('app', 'เด็ก'), ['class' => 'btn btn-danger btn-round']); ?>
                <?php endif; ?>

                <?php if ($model->hasCoi && !isset($model->start_time)) : ?>
                    <?= Html::button('<i class="icon md-alert-triangle"></i> COI', ['class' => 'btn btn-warning btn-round', 'role' => 'modal-remote', 'data-url' => \yii\helpers\Url::to(['meeting-agenda/add-coi-agenda', 'meetingId' => $model->id])]); ?>
                <?php endif; ?>
                <?php
                if (($model->checked_status == app\models\Meeting::CS_PENDING) && (($currentRole['role_id'] == \app\models\Role::STAFF && $model->checked_staff == Yii::$app->user->identity->id) || $currentRole['role_id'] == \app\models\Role::ADMIN)) {
                    echo Html::button('<i class="icon md-check"></i> ตรวจสอบผลการประชุม', [
                        'class' => 'btn btn-icon btn-primary btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ตรวจสอบผลการประชุมทั้งหมด'),
                        'data-url' => \yii\helpers\Url::to(['meeting/check-staff', 'id' => $model->id]),
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => Yii::t('app', 'ยืนยันการตรวจสอบผลการประชุม'),
                        'data-confirm-message' => Yii::t('app', 'ต้องการตรวจสอบผลการประชุมนี้ใช่หรือไม่ ?'),
                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                        'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                    ]);
                }
                ?>
                <?php
                if ($model->checked_secretary_first == \Yii::$app->user->identity->id) {
                    if (($currentRole['role_id'] == \app\models\Role::SECRETARY) && ($model->checked_status == app\models\Meeting::CS_STAFF_CHECKED)) {
                        echo Html::button('<i class="icon md-check"></i> ตรวจสอบผลการประชุม', [
                            'class' => 'btn btn-icon btn-warning btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ตรวจสอบผลการประชุมทั้งหมด'),
                            'data-url' => \yii\helpers\Url::to(['meeting/check-secretary', 'id' => $model->id]),
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการตรวจสอบผลการประชุม'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการตรวจสอบผลการประชุมนี้ใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                    }
                }
                ?>
                <?php
                if ($model->checked_secretary_second == \Yii::$app->user->identity->id) {
                    if (($currentRole['role_id'] == \app\models\Role::SECRETARY) && ($model->checked_status == app\models\Meeting::CS_PARTLY_CHECKED)) {
                        echo Html::button('<i class="icon md-check"></i> เลขาตรวจสอบผลการประชุม', [
                            'class' => 'btn btn-icon btn-warning btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ตรวจสอบผลการประชุมทั้งหมด'),
                            'data-url' => \yii\helpers\Url::to(['meeting/check-secretary', 'id' => $model->id]),
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการตรวจสอบผลการประชุม'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการตรวจสอบผลการประชุมนี้ใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                    }
                }
                ?>
                                    <?php
                if ($model->checked_president == \Yii::$app->user->identity->id) {
                    if (($currentRole['role_id'] == \app\models\Role::PRESIDENT || $currentRole['role_id'] == \app\models\Role::COPRESIDENT) && ($model->checked_status == app\models\Meeting::CS_PRE_CHECKED)) {
                        echo Html::button('<i class="icon md-check"></i> ประธานตรวจสอบผลการประชุม', [
                            'class' => 'btn btn-icon btn-warning btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ตรวจสอบผลการประชุมทั้งหมด'),
                            'data-url' => \yii\helpers\Url::to(['meeting/check-pre', 'id' => $model->id]),
                            'data-confirm' => false, 'data-method' => false, // for overide yii data api
                            'data-request-method' => 'post',
                            'data-toggle' => 'tooltip',
                            'data-confirm-title' => Yii::t('app', 'ยืนยันการตรวจสอบผลการประชุม'),
                            'data-confirm-message' => Yii::t('app', 'ต้องการตรวจสอบผลการประชุมนี้ใช่หรือไม่ ?'),
                            'data-confirm-ok' => Yii::t('app', 'ใช่'),
                            'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                        ]);
                    }
                }
                ?>
                <?php
                //                if (isset($model->end_time)) {
                //                    echo Html::a('<i class="icon fa-file-word-o"></i>', \yii\helpers\Url::to(['meeting/summary-doc', 'id' => $model->id]), ['class' => 'btn btn-icon btn-info btn-round', 'title' => Yii::t('app', 'ส่งออกรายงานการประชุม')]);
                //                }
                echo Html::a('<i class="icon fa-file-word-o"></i>', \yii\helpers\Url::to(['meeting/summary-doc-template', 'id' => $model->id]), ['class' => 'btn btn-icon btn-info btn-round', 'data-pjax' => 0, 'target' => '_blank', 'title' => Yii::t('app', 'ส่งออกรายงานการประชุม')]);
                ?>
                <?php if (!isset($model->start_time)) : ?>

                    <?=
                    Html::a('<i class="icon md-alarm-check"></i>', \yii\helpers\Url::to(['meeting/start', 'id' => $model->id]), ['class' => 'btn btn-icon btn-success btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'บันทึกเวลาเปิดประชุม')]);
                    //                    Html::button('<i class="icon md-alarm-check"></i>', ['class' => 'btn btn-icon btn-success btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'บันทึกเวลาเปิดประชุม'),
                    //                        'data-url' => \yii\helpers\Url::to(['meeting/start', 'id' => $model->id]),
                    //                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    //                        'data-request-method' => 'post',
                    //                        'data-toggle' => 'tooltip',
                    //                        'data-confirm-title' => Yii::t('app', 'ยืนยันการบันทึกเวลาเปิดประชุม'),
                    //                        'data-confirm-message' => Yii::t('app', 'ต้องการบันทึกเวลาเปิดประชุมใช่หรือไม่ ?'),
                    //                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                    //                        'data-confirm-cancel' => Yii::t('app', 'ไม่'),]);
                    ?>
                <?php endif; ?>
                <?php if (isset($model->start_time) && !isset($model->end_time)) : ?>
                    <?=
                    Html::a('<i class="icon md-alarm-off"></i>', \yii\helpers\Url::to(['meeting/end', 'id' => $model->id]), ['class' => 'btn btn-icon btn-round bg-red-600 white', 'role' => 'modal-remote', 'title' => Yii::t('app', 'บันทึกเวลาปิดประชุม')]);
                    //                    Html::button('<i class="icon md-alarm-off"></i>', ['class' => 'btn btn-icon btn-round bg-red-600 white', 'role' => 'modal-remote', 'title' => Yii::t('app', 'บันทึกเวลาปิดประชุม'),
                    //                        'data-url' => \yii\helpers\Url::to(['meeting/end', 'id' => $model->id]),
                    //                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                    //                        'data-request-method' => 'post',
                    //                        'data-toggle' => 'tooltip',
                    //                        'data-confirm-title' => Yii::t('app', 'ยืนยันการบันทึกเวลาปิดประชุม'),
                    //                        'data-confirm-message' => Yii::t('app', 'ต้องการบันทึกเวลาปิดประชุมใช่หรือไม่ ?'),
                    //                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                    //                        'data-confirm-cancel' => Yii::t('app', 'ไม่'),]);
                    ?>
                <?php endif; ?>
                <?php if (!isset($model->start_time) && !isset($model->end_time)) : ?>

                    <?=
                    Html::button('<i class="icon md-floppy"></i>', [
                        'class' => 'btn btn-icon btn-primary btn-round btn-save-meeting', 'title' => Yii::t('app', 'บันทึก'),
                        'data-url' => \yii\helpers\Url::to(['meeting/update-data', 'id' => $model->id]),
                        'data-toggle' => 'tooltip',
                    ]);
                    ?>
                <?php endif; ?>

                <?php if (!isset($model->start_time) && !isset($model->end_time)) : ?>
                    <?=
                    Html::button('<i class="icon md-delete"></i>', [
                        'class' => 'btn btn-icon btn-primary btn-round', 'role' => 'modal-remote', 'title' => Yii::t('app', 'ลบการประชุม'),
                        'data-url' => \yii\helpers\Url::to(['meeting/delete', 'id' => $model->id]),
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-toggle' => 'tooltip',
                        'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
                        'data-confirm-message' => Yii::t('app', 'ต้องการลบการประชุมนี้ใช่หรือไม่ ?'),
                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                        'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                    ]);
                    ?>
                <?php endif; ?>
                <?php yii\widgets\Pjax::end(); ?>
            </div>
            <div class="nav-tabs-horizontal">
                <ul class="nav nav-tabs nav-tabs-line margin-right-25" data-plugin="nav-tabs" role="tablist">
                    <li class="active" role="presentation">
                        <a data-toggle="tab" href="#general" aria-controls="general" role="tab"><?= Yii::t('app', 'ข้อมูลทั่วไป') ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#attendee" aria-controls="attendee" role="tab"><?= Yii::t('app', 'รายชื่อผู้เข้าร่วมประชุม') ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#agenda" aria-controls="agenda" role="tab"><?= Yii::t('app', 'วาระการประชุม') ?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a data-toggle="tab" href="#files" aria-controls="files" role="tab"><?= Yii::t('app', 'เอกสารประกอบการประชุม') ?>
                        </a>
                    </li>
                </ul>
                <div class="tab-content padding-vertical-15">
                    <div class="tab-pane active" id="general" role="tabpanel">
                        <?=
                        $this->render('_form', [
                            'model' => $model
                        ])
                        ?>
                    </div>
                    <div class="tab-pane" id="attendee" role="tabpanel">
                        <?=
                        $this->render('@app/views/meeting-person/index', [
                            'searchModel' => $mpSearch,
                            'dataProvider' => $mpProvider,
                        ]);
                        ?>
                    </div>
                    <div class="tab-pane" id="agenda" role="tabpanel">
                        <?=
                        $this->render('_agenda', [
                            'meeting' => $model,
                        ]);
                        ?>
                    </div>
                    <div class="tab-pane" id="files" role="tabpanel">
                        <?=
                        $this->render('_meeting-files', [
                            'meeting' => $model,
                        ]);
                        ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var _scrollTop = 0;

    function editorKeySave(evt) {
        if (evt.data.keyCode == 1114195) {
            // Do something...
            console.log(evt.data.keyCode);
            // Cancel the event, so other listeners will not be executed and
            // the keydown's default behavior will be prevented.
            $('.btn-save-update-info').click();
            evt.cancel();
        }
    }

    function saveInfo() {
        var form = $('.meeting-agenda-form').find('form'); //$(this).closest('form');
        //        form.submit();
        //        console.log(form);

        $.ajax({
            url: form.attr('action'),
            data: form.serialize(),
            method: 'POST',
            //            dataType: 'JSON',
            success: function(res, textStatus, jqXHR) {
                $('.agenda-info').html(res);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function() {});
            }
        });
    }
</script>
<?php
$js = <<<js
    $('#agenda-list-pjax').on('pjax:end', function() {
        console.log('scrollTop:' + _scrollTop);
        $('#agenda-list-pjax > div').scrollTop(_scrollTop);
    })
    
    $('.btn-save-meeting').click(function() {
        var data;

        // Test if browser supports FormData which handles uploads
        if (window.FormData) {
            data = new FormData($('#form-meeting')[0]);
        } else {
            // Fallback to serialize
            data = $('#form-meeting').serializeArray();
        }
        modal.doRemote(
            $(this).data('url'),
            'POST',
            data
        );
//        $.ajax({
//            url: $(this).data('url'),
//            data: $('#form-meeting').serialize(),
//            method: 'POST',
//            dataType: 'JSON',
//            success: function(res, textStatus, jqXHR) {
//                
//            },
//            error: function(jqXHR, textStatus, errorThrown) {
//                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
//            }
//        });
    });
        
//    $('.btn-save-update-info').click(saveInfo);
    $('body').on('click', '.btn-save-update-info', saveInfo);  
    hotkeys.filter = function(event){
//                console.log(event);
        return true;
    }
    hotkeys('ctrl+s', function(event,handler){
        event.preventDefault();
        $('.btn-save-update-info').click();
    });
js;
$this->registerJs($js);
