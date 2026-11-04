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
    <div class="col-md-3">

        <div class="dd">
            <ol class="dd-list" data-container="0">
                <?php
                $meetingAgendas = $meeting->getMeetingAgendas()->joinWith('agenda')->isDeleted(FALSE)->parentAgenda(NULL)->andWhere(['or',['=', 'agenda.id', 3],
                           ['=', 'agenda.id', 4],])->orderBy('sort ASC')->all();
                foreach ($meetingAgendas as $ma):
                    ?>
                    <li class="dd-item" data-id="<?= $ma->id ?>" data-sortable="<?= $ma->sortable ?>" data-parent="<?= isset($ma->parent_id) ? $ma->parent_id : 0 ?>">
                        <div class="dd-handle agenda-item <?= $ma->need_resolution ? "agenda-clickable" : "" ?> " style="">
                            <?= $ma->fullTitle ?>

                        </div>
                        <?php
                        $secMas = $ma->getMeetingAgendas()->isDeleted(FALSE)->orderBy('sort ASC')->all();
                        if (count($secMas) > 0):
                            ?>
                            <ol class="dd-list" data-container="<?= $ma->id ?>">
                                <?php foreach ($secMas as $secMa): ?>
                                    <li class="dd-item" data-id="<?= $secMa->id ?>" data-sortable="<?= $secMa->sortable; ?>" data-parent="<?= $secMa->parent_id ?>">
                                        <?php $thMas = $secMa->getMeetingAgendas()->isDeleted(FALSE)->orderBy('sort ASC')->all(); ?>
                                        <?php if (isset($secMa->agenda)) : ?>
                                            <div class="dd-handle agenda-item" style="">
                                                <?= $secMa->sort_label ?>
                                                <div class="pull-right">
                                                    <span class="label label-primary"><?= count($thMas) ?> <?= Yii::t('app', 'โครงการ') ?></span>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="dd-handle agenda-item agenda-clickable" style="">
                                                <?= $secMa->fullTitle ?>

                                            </div>

                                        <?php endif; ?>
                                        <?php if (count($thMas) > 0): ?>
                                            <ol class="dd-list" data-container="<?= $secMa->id ?>">
                                                <?php foreach ($thMas as $thMa): ?>
                                                    <li class="dd-item" data-id="<?= $thMa->id ?>" data-sortable="<?= $thMa->sortable; ?>" data-parent="<?= $thMa->parent_id ?>">
                                                        <div class="dd-handle agenda-item agenda-clickable" style="">
                                                            <?= $thMa->fullTitle ?>
                                                            <div class="pull-right">
                                                                <?php if ($thMa->submission->project->is_child_project): ?>
                                                                    <?=
                                                                    Html::button('<i class="icon md-face"></i>'
                                                                            , ['class' => 'btn btn-icon btn-danger btn-round btn-xs', 'title' => Yii::t('app', 'โครงการเด็ก')]
                                                                    );
                                                                    ?>
                                                                <?php endif; ?>
                                                                <?php if (count($thMa->coiPeople) > 0): ?>
                                                                    <?=
                                                                    Html::button('<i class="icon md-alert-triangle"></i>'
                                                                            , ['class' => 'btn btn-icon btn-warning btn-round btn-xs', 'title' => 'COI']
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
        $reportUrl = yii\helpers\Url::to(['submission-committee/report']);
        $js = <<<js
    function loadInfo(submissionId) {
//        console.log(el);
//        $.pjax.reload('#agenda-info-pjax', {url: '{$reportUrl}?id=' + id, timeout: false, push: false, replace: true});
        $.ajax({
            url: '{$reportUrl}',
            data: {submissionId: {$thMa->submission_id}},
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
    <div class="col-md-9">
        <div class="panel">
            <div class="panel-body agenda-info">
            </div>
        </div>
    </div>
</div>

