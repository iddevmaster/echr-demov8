<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\helpers\Html;
use yii\widgets\Pjax;

app\assets\SortableAsset::register($this);
app\assets\NestableAsset::register($this);
?>

<div class="row">
    <?php Pjax::begin(['id' => 'agenda-list-pjax', 'timeout' => FALSE, 'enablePushState' => FALSE, 'enableReplaceState' => FALSE]); ?>
    <div class="col-md-3" style="height: calc(100vh - 250px); overflow-y:auto;">

        <div class="dd">
            <ol class="dd-list" data-container="0">
                <?php
                $meetingAgendas = $meeting->getMeetingAgendas()->isDeleted(FALSE)->parentAgenda(NULL)
                    ->notCOIMeeting()
                    ->orderBy('sort ASC')->all();
                foreach ($meetingAgendas as $ma) :
                ?>
                    <li class="dd-item" data-id="<?= $ma->id ?>" data-sortable="<?= $ma->sortable ?>" data-parent="<?= isset($ma->parent_id) ? $ma->parent_id : 0 ?>">
                        <div class="dd-handle agenda-item <?= $ma->need_resolution ? "agenda-clickable" : "" ?> " style="">
                            <?= $ma->fullTitle ?>
                            <?php if ($ma->addable) : ?>
                                <span class="pull-right">
                                    <?php
                                    echo Html::a(
                                        '<i class="icon md-plus"></i>',
                                        ['meeting-agenda/create', 'meetingId' => $meeting->id, 'parentId' => $ma->id],
                                        ['class' => 'btn btn-icon btn-primary btn-round btn-xs', 'role' => 'modal-remote', 'title' => Yii::t('app', 'เพิ่มวาระ')]
                                    );
                                    ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <?php
                        $secMas = $ma->getMeetingAgendas()->isDeleted(FALSE)
                            ->notCOIMeeting()
                            ->orderBy('sort ASC')->all();
                        if (count($secMas) > 0) :
                        ?>
                            <ol class="dd-list" data-container="<?= $ma->id ?>">
                                <?php foreach ($secMas as $secMa) : ?>
                                    <li class="dd-item" data-id="<?= $secMa->id ?>" data-sortable="<?= $secMa->sortable; ?>" data-parent="<?= $secMa->parent_id ?>">
                                        <?php $thMas = $secMa->getMeetingAgendas()->isDeleted(FALSE)
                                            ->notCOIMeeting()
                                            ->orderBy('sort ASC')->all(); ?>
                                        <?php if (isset($secMa->agenda)) : ?>
                                            <div class="dd-handle agenda-item" style="">
                                                <?= $secMa->sort_label ?>
                                                <div class="pull-right">
                                                    <span class="label label-primary"><?= count($thMas) ?> <?= Yii::t('app', 'โครงการ') ?></span>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div class="dd-handle agenda-item agenda-clickable" style="">
                                                <?= $secMa->fullTitle ?>

                                            </div>
                                            <div class="agenda-actions">
                                                <?=
                                                Html::button(
                                                    '<i class="icon md-delete"></i>',
                                                    [
                                                        'class' => 'btn btn-icon btn-danger btn-xs', 'title' => 'ลบ',
                                                        'role' => 'modal-remote', 'data-url' => \yii\helpers\Url::to(['meeting-agenda/delete', 'id' => $secMa->id]),
                                                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                                        'data-request-method' => 'post',
                                                        'data-confirm-title' => Yii::t('app', 'ยืนยันการลบ'),
                                                        'data-confirm-message' => Yii::t('app', 'ต้องการลบวาระนี้ใช่หรือไม่ ?'),
                                                        'data-confirm-ok' => Yii::t('app', 'ใช่'),
                                                        'data-confirm-cancel' => Yii::t('app', 'ไม่'),
                                                    ]
                                                );
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if (count($thMas) > 0) : ?>
                                            <ol class="dd-list" data-container="<?= $secMa->id ?>">
                                                <?php foreach ($thMas as $thMa) : ?>
                                                    <li class="dd-item" data-id="<?= $thMa->id ?>" data-sortable="<?= $thMa->sortable; ?>" data-parent="<?= $thMa->parent_id ?>">
                                                        <div class="dd-handle agenda-item agenda-clickable" style="">
                                                            <?= $thMa->fullTitle ?>
                                                            <div class="pull-right">
                                                                <?php if ($thMa->submission->project->is_child_project) : ?>
                                                                    <?=
                                                                    Html::button(
                                                                        '<i class="icon md-face"></i>',
                                                                        ['class' => 'btn btn-icon btn-danger btn-round btn-xs', 'title' => Yii::t('app', 'โครงการเด็ก')]
                                                                    );
                                                                    ?>
                                                                <?php endif; ?>
                                                                <?php if (count($thMa->coiPeople) > 0) : ?>
                                                                    <?=
                                                                    Html::button(
                                                                        '<i class="icon md-alert-triangle"></i>',
                                                                        ['class' => 'btn btn-icon btn-warning btn-round btn-xs', 'title' => 'COI']
                                                                    );
                                                                    ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ol>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach;
                                ?>
                            </ol>
                        <?php endif; ?>
                    </li>



                <?php
                endforeach;
                ?>
            </ol>
        </div>
        <?php
        $sortUrl = yii\helpers\Url::to(['meeting-agenda/update-sort']);
        $agendaInfoUrl = yii\helpers\Url::to(['meeting-agenda/update-info']);
        $js = <<<js
        
    function loadInfo(id) {
//        console.log(el);
//        $.pjax.reload('#agenda-info-pjax', {url: '{$agendaInfoUrl}&id=' + id, timeout: false, push: false, replace: true});
        $.ajax({
            url: '{$agendaInfoUrl}',
            data: {id: id},
            method: 'GET',
//            dataType: 'JSON',
            success: function(res, textStatus, jqXHR) {
                $('.agenda-info').html(res);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
            }
        });
    }
    $('.dd').nestable({
        callback: function(l,e){
            // l is the main container
            // e is the element that was moved
//            console.log(e);
            var parentId = $(e).data('parent');
            var data = $('.dd').nestable('serialize');
            
            var filter = data.filter((d) => {
                return d.id == parentId;
            });
//            console.log(filter);
            var _childFilter = [];
            if (filter.length == 0) {
                data.filter((d) => {
                    
                    if (!d.children) {
//                        console.log(d);
                        return false;
                    }
                    var childFilter = d.children.filter((c) => {
                        return c.id == parentId;
                    });
                    //console.log(childFilter);
                    if (childFilter.length > 0) {
                        _childFilter = childFilter;
                    }
                });
                filter = _childFilter;
            }
            
//            console.log(filter);
//            console.log(filter[0].children);
            _scrollTop = $('#agenda-list-pjax > div').scrollTop();
            console.log('get scrollTop:' + _scrollTop);
            $.ajax({
                url: '{$sortUrl}',
                data: {meetingAgendas: filter[0].children},
                method: 'POST',
                dataType: 'JSON',
                success: function(res, textStatus, jqXHR) {
                    $.pjax.reload('#agenda-list-pjax', {push: false, replace: false});
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function(){});
                }
            });
            loadInfo($(e).data('id'));
//            $(e).click();
//            return true;
        },
        onDragStart: function (l, e) {
            var sortable = $(e).data('sortable');
            if (sortable == 0) {
                return false;
            }
        },
        beforeDragStop: function (l, e, p) {
            var srcParent = $(e).data('parent');
            var dstParent = $(p).data('container');
//            console.log(l);
//            console.log(e);
//            console.log(p);
            if (srcParent != dstParent) {
                return false;
            }
        }
    });
                
    $('li[data-sortable="0"] > .agenda-clickable').click(function() {
//        console.log(this);
        loadInfo($(this).parent().data('id'));
    });
                
js;
        $this->registerJs($js);
        ?>
        <?php Pjax::end(); ?>
    </div>
    <div class="col-md-9" style="height: calc(100vh - 250px); overflow-y:auto;">
        <div class="panel">
            <div class="panel-body agenda-info">
            </div>
        </div>
    </div>
</div>