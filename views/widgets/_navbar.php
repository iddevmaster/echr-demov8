<?php

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\helpers\Url;

$languageItem = new cetver\LanguageSelector\items\DropDownLanguageItem(Yii::$app->util->getLanguageItems());
$currentRole = Yii::$app->session->get('currentRole');
?>
<nav class="site-navbar navbar navbar-inverse navbar-fixed-top navbar-mega" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle hamburger hamburger-close navbar-toggle-left hided"
                data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggle collapsed" data-target="#site-navbar-collapse"
                data-toggle="collapse">
            <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            <?=
            Html::img("@web/images/logo.png", [
                'class' => 'navbar-brand-logo',
                'title' => 'Submission Online',
                'style' => 'height: 55px;margin-top: -15px;'
            ]);
            ?>
            <span class="navbar-brand-text hidden-xs-down"></span>
        </div>

    </div>
    <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <!-- Navbar Toolbar -->
            <?php
            $alerts = Yii::$app->user->identity->getLatestAlerts();
            $items = \app\models\Alert::buildAlertItems($alerts);
            echo Nav::widget([
                'options' => ['class' => 'nav navbar-toolbar'],
                'dropDownCaret' => '',
                'encodeLabels' => FALSE,
                'items' => [
                    [
                        'label' => Html::tag('i', Html::tag('span', 'Toggle menubar', ['class' => 'sr-only']) . Html::tag('span', '', ['class' => 'hamburger-bar']), ['class' => 'icon hamburger hamburger-arrow-left']),
//                    'url' => Url::to(['site/dashboard']),
                        'encode' => FALSE,
                        'options' => ['class' => 'nav-item hidden-float', 'id' => 'toggleMenubar'],
                        'linkOptions' => ['class' => 'nav-link', 'data-toggle' => 'menubar', 'href' => '#', 'role' => 'button'],
//                    'visible' => Yii::$app->user->can('core.site.dashboard'),
                    ],
                ],
            ]);

            $currentRole = Yii::$app->session->get('currentRole');
//            \yii\helpers\VarDumper::dump($currentRole);
            echo Nav::widget([
                'options' => ['class' => 'nav navbar-toolbar navbar-right navbar-toolbar-right text-right margin-right-0'],
                'dropDownCaret' => '',
                'encodeLabels' => FALSE,
                'items' => [
                    $languageItem->toArray(),
                    [
                        'label' => '<i class="icon md-notifications"></i><span class="badge badge-danger up alert-count">' . count($alerts) . '</span>',
                        'options' => ['style' => 'min-width: 40px;'],
                        'linkOptions' => [
                            'data-animation' => 'scale-up',
                            'role' => 'button',
                        ],
                        'dropDownOptions' => [
                            'class' => 'dropdown-menu-right dropdown-menu-media',
                            'id' => 'alert-dropdown',
                            'role' => 'menu',
                        ],
                        'items' => $items
                    ],
                    [
                        'label' =>
//                                    Html::tag('div', Yii::$app->user->identity->employee->fullName . '<br><small>' . Yii::$app->user->identity->employee->role->name . '</small>', ['class' => 'padding-right-5 inline-block line-height-16'])
                        Html::tag('div', Yii::$app->user->identity->person->i18nFullName . '<br><small>' . Yii::$app->user->identity->person->i18nCurrentRoleName . '</small>', ['class' => 'padding-right-5 inline-block line-height-16'])
                        . Html::beginTag('span', ['class' => 'avatar avatar-online vertical-align-top'])
//                            . Html::img('@web/ionicons/png/512/android-contact.png')
//                        . Html::img('@web/images/material/ic_face_white_48dp_2x.png')
                        . Html::tag('span', '', ['class' => 'icon md-face font-size-40'])
                        . Html::tag('i')
                        . Html::endTag('span'),
                        'linkOptions' => [
                            'class' => 'navbar-avatar padding-10 font-weight-900',
                            'data-animation' => 'scale-up',
                            'role' => 'button',
                        ],
                        'dropDownOptions' => [
//                            'class' => 'dropdown-menu-right dropdown-menu-media',
                            'role' => 'menu',
                        ],
                        'items' => [
                            [
                                'label' => Html::tag('i', '', ['class' => 'icon md-account']) . Yii::t('app', 'จัดการข้อมูลส่วนตัว'),
                                'url' => Url::to(['person/update', 'id' => $currentRole['person_id']]),
                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
                            ],
                            [
                                'label' => Html::tag('i', '', ['class' => 'icon md-swap']) . Yii::t('app', 'เปลี่ยนหน้าที่'),
                                'url' => Url::to(['site/change-role']),
                                'visible' => Yii::$app->user->identity->person->hasMoreRoles,
                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
                            ],
//                            [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-download']) . Yii::t('app', 'ดาวน์โหลดคู่มือการใช้งาน'),
//                                'url' => Url::to('@web/uploads/usermanual.pdf'),
//                                'visible' => $currentRole['role_id'] == \app\models\Role::RESEARCHER,
//                                'encode' => FALSE,
//                                'linkOptions' => ['target' => '_blank'],],
//                            [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-download']) . Yii::t('app', 'ดาวน์โหลดคู่มือการใช้งาน'),
//                                'url' => Url::to('@web/images/manual-coordinator.pdf'),
//                                'visible' => $currentRole['role_id'] == \app\models\Role::COORDINATOR,
//                                'encode' => FALSE,
//                                'linkOptions' => ['target' => '_blank'],],
                            '<li role="separator" class="divider"></li>',
                            [
                                'label' => Html::tag('i', '', ['class' => 'icon md-power']) . Yii::t('app', 'ออกจากระบบ'),
                                'url' => Url::to(['site/logout']),
                                'encode' => FALSE,
                                'linkOptions' => ['class' => 'btn-logout'],
                            ],
                        ],
                    ],
                ],
            ]);
//            echo Nav::widget([
//                'options' => ['class' => 'nav navbar-toolbar navbar-right navbar-toolbar-right text-right margin-right-0'],
//                'dropDownCaret' => '',
//                'encodeLabels' => FALSE,
//                'items' => [
//                        [
//                        'label' => Html::tag('i', '', ['class' => 'icon md-email'])
//                        . Html::tag('span', '', ['class' => 'badge badge-danger up'])
//                        . ' 5 '
//                        . Html::endTag('span'),
//                        'linkOptions' => [
//                            'class' => 'navbar-avatar padding-10 font-weight-900',
//                            'data-animation' => 'scale-up',
//                            'role' => 'button',
//                        ],
//                        'dropDownOptions' => [
////                            'class' => 'dropdown-menu-right dropdown-menu-media',
//                            'role' => 'menu',
//                        ],
//                        'items' => [
//                                [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-star']) . 'จำนวนงานวิจัยใหม่ 2 รายการ',
//                                'url' => Url::to(['site/logout']),
//                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
//                            ],
//                                [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-star']) . 'จำนวนงานวิจัยใหม่ 1 รายการ',
//                                'url' => Url::to(['site/logout']),
//                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
//                            ],
//                                [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-star']) . 'จำนวนงานวิจัยใหม่ 2 รายการ',
//                                'url' => Url::to(['site/logout']),
//                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
//                            ],
//                        ],
//                    ],
//                ],
//            ]);
//            echo Nav::widget([
//                'options' => ['class' => 'nav navbar-toolbar navbar-right navbar-toolbar-right text-right margin-right-0'],
//                'dropDownCaret' => '',
//                'encodeLabels' => FALSE,
//                'items' => [
//                        [
//                        'label' => Html::tag('i', '', ['class' => 'icon md-notifications'])
//                        . Html::tag('span', '', ['class' => 'badge badge-danger up'])
//                        . ' 2 '
//                        . Html::endTag('span'), 'linkOptions' => [
//                            'class' => 'navbar-avatar padding-10 font-weight-900',
//                            'data-animation' => 'scale-up',
//                            'role' => 'button',
//                        ],
//                        'dropDownOptions' => [
////                            'class' => 'dropdown-menu-right dropdown-menu-media',
//                            'role' => 'menu',
//                        ],
//                        'items' => [
//                                [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-star']) . 'จำนวนงานวิจัยใหม่ที่แก้ไขแล้ว 1 รายการ',
//                                'url' => Url::to(['site/logout']),
//                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
//                            ],
//                                [
//                                'label' => Html::tag('i', '', ['class' => 'icon md-star']) . 'แบบประเมินล่าสุด 1 รายการ',
//                                'url' => Url::to(['site/logout']),
//                                'encode' => FALSE,
//                                'linkOptions' => ['class' => 'btn-logout'],
//                            ],
//                        ],
//                    ],
//                ],
//            ]);
            ?>

            <div class="navbar-brand navbar-brand-left padding-20" data-toggle="gridmenu">
                <?=
                Html::img("@web/images/logo.png", [
                    'class' => 'navbar-brand-logo',
                    'title' => 'Submission Online',
                    'style' => 'height: 55px;margin-top: -15px;'
                ]);
                ?>
            <!--<img class="navbar-brand-logo" src="../assets/images/logo.png" title="Remark">-->
                <span class="navbar-brand-text"><?= yii::t('app', 'EC:Online Submission System '); ?></span>
            </div>
        </div>
    </div>
</nav>