<?php

use yii\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

$currentRole = Yii::$app->session->get('currentRole');
$mointor = \app\models\Submission::find()->isDeleted(false)->projectViewer(\Yii::$app->user->id)->one();
?>
<div class="site-menubar" style="">
    <div class="site-menubar-body">
        <div>
            <div>
                <?php
                echo Menu::widget([
                    'encodeLabels' => FALSE,
                    'options' => [
                        'class' => 'site-menu',
                        
                        'data-plugin' => 'menu',
                    ],
                    'itemOptions' => [
                        'class' => 'site-menu-item',
                    ],
                    'submenuTemplate' => '<ul class="site-menu-sub">{items}</ul>',
                    'items' => [
                        // Important: you need to specify url as 'controller/action',
                        // not just as 'controller' even if default action is used.
//                                ['label' => '<i class="site-menu-icon md-home" aria-hidden="true"></i>
//                                                        <span class="site-menu-title">หน้าหลัก</span>', 'encode' => false, 'url' => ['site/index']],
                        // 'Products' menu item will be selected as long as the route is 'product/index'
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-home']) . Html::tag('span', yii::t('app', 'หน้าหลัก'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'หน้าหลัก')]), 'url' => ['site/index'], 'visible' => Yii::$app->util->checkPermission('site.index')],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-home']) . Html::tag('span', yii::t('app', 'รายงานจำนวนชั่วโมงการประชุม'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'รายงานจำนวนชั่วโมงการประชุม')]), 'url' => ['meeting/committee-report'], 'visible' => $currentRole['role_id'] == \app\models\Role::COMMITTEE],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-settings']) . Html::tag('span', yii::t('app', 'ข้อมูลตั้งต้น'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'ข้อมูลตั้งต้น')]), 'url' => ['site/master-list'], 'visible' => Yii::$app->util->checkPermission('site.master-list') && ($currentRole['role_id'] == app\models\Role::ADMIN)],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-receipt']) . Html::tag('span', yii::t('app', 'ส่งเอกสารงานวิจัยใหม่'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'ส่งเอกสารงานวิจัยใหม่')]), 'url' => ['submission/new'], 'visible' => $currentRole['role_id'] == \app\models\Role::RESEARCHER or $currentRole['role_id'] == \app\models\Role::COORDINATOR or $currentRole['role_id'] == \app\models\Role::STAFF],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-assignment-o']) . Html::tag('span', yii::t('app', 'ส่งเอกสารงานวิจัยต่อเนื่อง'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'ส่งเอกสารงานวิจัยต่อเนื่อง')]), 'url' => ['submission/continue'], 'visible' => $currentRole['role_id'] == \app\models\Role::RESEARCHER or $currentRole['role_id'] == \app\models\Role::COORDINATOR ],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-assignment-check']) . Html::tag('span', yii::t('app', 'ส่งเอกสารโครงการเก่าก่อนมีระบบ Online Submission'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'ส่งเอกสารโครงการเก่าก่อนมีระบบ Online Submission')]), 'url' => ['submission/new-certified'], 'visible' => $currentRole['role_id'] == \app\models\Role::STAFF],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon ion-android-contacts']) . Html::tag('span', yii::t('app', 'งานวิจัยที่เป็นนักวิจัยร่วม'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'งานวิจัยที่เป็นนักวิจัยร่วม')]), 'url' => ['submission/index-not-isleader'], 'visible' => $currentRole['role_id'] == \app\models\Role::RESEARCHER ],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon ion-android-contact']) . Html::tag('span', yii::t('app', 'งานวิจัยที่เป็นอาจารย์ที่ปรึกษา'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'งานวิจัยที่เป็นอาจารย์ที่ปรึกษา')]), 'url' => ['submission/index-isconsultant'], 'visible' => $currentRole['role_id'] == \app\models\Role::RESEARCHER ],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon ion-android-contact']) . Html::tag('span', yii::t('app', 'งานวิจัยที่กำหนดเป็น Monitor'), ['class' => 'site-menu-title', 'title' => yii::t('app', 'งานวิจัยที่กำหนดเป็น Monitor')]), 'url' => ['submission/index-ismonitor'], 'visible' => $currentRole['role_id'] == \app\models\Role::COORDINATOR  && isset($mointor)],

                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon md-file-text']) . Html::tag('span', yii::t('app', 'จัดการโครงการวิจัย'), ['class' => 'site-menu-title']) . Html::tag('span', '', ['class' => 'site-menu-arrow']), 'visible' => Yii::$app->util->checkPermission('project.*') && $currentRole['role_id'] == app\models\Role::STAFF, 'url' => 'javascript:void(0)',
                            'options' => ['class' => 'site-menu-item has-sub'],
                            'items' => [
                                ['label' => Html::tag('span', yii::t('app', 'รายการงานวิจัยใหม่'), ['class' => 'site-menu-title']), 'url' => ['submission/index', 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_NEW], 'visible' => Yii::$app->util->checkPermission('project.*')],
                                ['label' => Html::tag('span', yii::t('app', 'รายการงานวิจัยต่อเนื่อง'), ['class' => 'site-menu-title']), 'url' => ['submission/index', 'typeGroup' => \app\models\SubmissionTypeGroup::GROUP_CONT], 'visible' => Yii::$app->util->checkPermission('project.*')],
                            ]],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-accounts-alt']) . Html::tag('span', yii::t('app', 'จัดการประชุม'), ['class' => 'site-menu-title']) . Html::tag('span', '', ['class' => 'site-menu-arrow']), 'visible' => Yii::$app->util->checkPermission('meeting.*'), 'url' => 'javascript:void(0)',
                            'options' => ['class' => 'site-menu-item has-sub'],
                            'items' => [
                                ['label' => Html::tag('span', yii::t('app', 'การจัดการประชุม'), ['class' => 'site-menu-title']), 'url' => ['meeting/index'], 'visible' => Yii::$app->util->checkPermission('meeting.*')],
//                                    ['label' => Html::tag('span', yii::t('app', 'รายการงานประชุม'), ['class' => 'site-menu-title']), 'url' => ['meeting/index'], 'visible' => Yii::$app->util->checkPermission('agenda-meeting.*')],
//                                    ['label' => Html::tag('span', yii::t('app', 'ออกหนังสือแจ้งผลการประชุม'), ['class' => 'site-menu-title']), 'url' => ['meeting-agenda/index'], 'visible' => Yii::$app->util->checkPermission('agenda-meeting.*')],
                            ]],
                        ['label' => Html::tag('i', '', ['class' => 'site-menu-icon icon md-view-headline']) . Html::tag('span', yii::t('app', 'รายงาน'), ['class' => 'site-menu-title']), 'url' => ['site/report-list'], 'visible' => Yii::$app->util->checkPermission('report.report-list') && $currentRole['role_id'] != app\models\Role::RESEARCHER],
                    ],
                ]);
                ?>


            </div>
        </div>
    </div>
</div>