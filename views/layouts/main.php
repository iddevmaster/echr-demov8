<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use app\models\Person;
use kartik\dialog\Dialog;
use yii\web\JsExpression;
use yii\widgets\Menu;

AppAsset::register($this);
app\assets\RemarkIconsAsset::register($this);
johnitvn\ajaxcrud\CrudAsset::register($this);
\app\assets\TabsAsset::register($this);
app\assets\ToastrAsset::register($this);

echo Dialog::widget([
    'libName' => 'dlgError',
    'options' => [
        'type' => Dialog::TYPE_DANGER,
        'title' => 'ข้อผิดพลาด',
        //        'buttons' => [
        //            [
        //                'id' => 'dlgError-ok-btn',
        //                'label' => 'ตกลง',
        //                'icon' => Dialog::ICON_OK,
        //                'cssClass' => 'btn btn-danger btn-raised',
        //                'action' => new JsExpression("function(dialog) {
        //                    dialog.close();
        //                }")
        //            ],
        //        ]
    ], // default options
]);

echo Dialog::widget([
    'libName' => 'dlgPrimary',
    'dialogDefaults' => [
        Dialog::DIALOG_CONFIRM => [
            'type' => Dialog::TYPE_WARNING,
            'title' => Yii::t('app', 'ยืนยันข้อมูล'),
            'btnOKClass' => 'btn-warning',
            'btnOKLabel' => Yii::t('app', 'ตกลง'),
            'btnCancelLabel' => Yii::t('app', 'ยกเลิก')
        ],
        Dialog::DIALOG_ALERT => [
            'type' => Dialog::TYPE_PRIMARY,
            'title' => Yii::t('app', 'ข้อมูล'),
            'buttonLabel' => Yii::t('app', 'ตกลง'),
        ]
    ],
    'options' => [
        //        'type' => Dialog::TYPE_PRIMARY,
        //        'title' => 'ข้อผิดพลาด',
    ], // default options
]);

$appId = Yii::$app->id;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="nNnCWzTob4-dr7sMiP8rXtbplowG2lUnngBCY-on5oc" />
    <meta name="robots" content="noindex">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script>
        Breakpoints();
    </script>
</head>

<body class="">
    <?php $this->beginBody() ?>
    <?php
    if (!Yii::$app->user->isGuest) {
        echo $this->renderFile('@app/views/widgets/_navbar.php');
        echo $this->renderFile('@app/views/widgets/_sidebar.php');
    }
    ?>

    <!-- Page -->
    <div class="page animsition">
        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="page-header page-toolbar padding-vertical-10">
                <h1 class="page-title <?= isset($this->params['title-color']) ? $this->params['title-color'] : "" ?>"><?= $this->title ?> <?= isset($this->params['button']) ? $this->params['button'] : "" ?></h1>
                <div class="page-header-actions">
                    <?=
                    \yii\widgets\Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        //                            'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        //                            'activeItemTemplate' => "<li class='breadcrumb-item active'>{link}</li>\n"
                    ])
                    ?>
                </div>
            </div>

        <?php endif; ?>
        <div class="page-content container-fluid has-page-toolbar">
            <?= $content ?>
        </div>
    </div>
    <?php
    //    NavBar::begin([
    //        'brandLabel' => 'My Company',
    //        'brandUrl' => Yii::$app->homeUrl,
    //        'options' => [
    //            'class' => 'navbar-inverse navbar-fixed-top',
    //        ],
    //    ]);
    //    echo Nav::widget([
    //        'options' => ['class' => 'navbar-nav navbar-right'],
    //        'items' => [
    //            ['label' => 'Home', 'url' => ['/site/index']],
    //            ['label' => 'About', 'url' => ['/site/about']],
    //            ['label' => 'Contact', 'url' => ['/site/contact']],
    //            Yii::$app->user->isGuest ? (
    //                ['label' => 'Login', 'url' => ['/site/login']]
    //            ) : (
    //                '<li>'
    //                . Html::beginForm(['/site/logout'], 'post')
    //                . Html::submitButton(
    //                    'Logout (' . Yii::$app->user->identity->username . ')',
    //                    ['class' => 'btn btn-link logout']
    //                )
    //                . Html::endForm()
    //                . '</li>'
    //            )
    //        ],
    //    ]);
    //    NavBar::end();
    ?>

    <?php
    Modal::begin([
        "id" => "ajaxCrudModal",
        "options" => [
            "tabindex" => FALSE,
            'class' => 'modal-primary',
        ],
        "footer" => "", // always need it for jquery plugin
    ])
    ?>
    <?php Modal::end(); ?>
    <?php $this->endBody() ?>
    <script>
        $.ajaxSetup({
            data: <?=
                    \yii\helpers\Json::encode([
                        \yii::$app->request->csrfParam => \yii::$app->request->csrfToken,
                    ])
                    ?>
        });
        (function(document, window, $) {
            'use strict';
            var Site = window.Site;
            $(document).ready(function() {
                Site.run();
                $('.btn-logout').on('click', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: '<?= Url::to(['site/logout']) ?>',
                        method: 'POST',
                        dataType: 'JSON',
                        success: function(data, textStatus, jqXHR) {
                            window.location = data.location;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            dlgError.dialog(textStatus + ': ' + jqXHR.status + ' ' + errorThrown + '</br>' + jqXHR.responseText, function() {});
                        }
                    });
                });
                setTimeout(function() {
                    updateAlertMenu();
                    checkUnshownAlert();
                }, 500);
            });
        })(document, window, jQuery);

        function acknowledge(alertId) {
            //                alert(alertId);
            //                var alertCount = $('.alert-count').text();
            //                $('.alert-count').text(--alertCount);
            //                $(event.target).hide();
            //                var $item = $(event.target).closest('.list-group-item');
            //                $item.addClass('bg-grey-300');
            //                $item.hide('slow');

            $.ajax({
                url: '<?= Url::to(['alert/acknowledge']) ?>',
                data: {
                    id: alertId
                },
                success: function(data, textStatus, jqXHR) {
                    updateAlertMenu();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    dlgError.alert(textStatus + ": " + jqXHR.status + " " + errorThrown + "</br>" + jqXHR.responseText);
                }
            });
        }

        function acknowledgeAll() {
            $.ajax({
                url: '<?= Url::to(['alert/acknowledge-all']) ?>',
                success: function(data, textStatus, jqXHR) {
                    updateAlertMenu();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    dlgError.alert(textStatus + ": " + jqXHR.status + " " + errorThrown + "</br>" + jqXHR.responseText);
                }
            });
        }

        function updateAlertMenu() {
            $.ajax({
                url: '<?= Url::to(['alert/get-alert-list']) ?>',
                success: function(data, textStatus, jqXHR) {
                    $('.alert-count').text(data.count);
                    var li = $('#alert-dropdown').parent();
                    $('#alert-dropdown').remove();
                    li.append(data.menu);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    dlgError.alert(textStatus + ": " + jqXHR.status + " " + errorThrown + "</br>" + jqXHR.responseText);
                }
            });
        }

        function alertShown(alertId) {
            //                alert(alertId);
            $.ajax({
                url: '<?= Url::to(['alert/alert']) ?>',
                data: {
                    id: alertId
                },
                success: function(data, textStatus, jqXHR) {

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    dlgError.alert(textStatus + ": " + jqXHR.status + " " + errorThrown + "</br>" + jqXHR.responseText);
                }
            });
        }

        function checkUnshownAlert() {
            var url = '<?= Url::to(['submission/project-submission']) ?>';
            $.ajax({
                url: '<?= Url::to(['alert/get-unshown-alerts', 't' => time()]) ?>',
                success: function(data, textStatus, jqXHR) {
                    var n = data.length;
                    //                        console.log(data)
                    for (var i = 0; i < n; i++) {
                        var color = 'success';
                        var onclick = '$(this).closest(\'.toast\').fadeOut(1000);acknowledge(' + data[i].id + ');';
                        color = 'error';
                        var op = url.indexOf('?') == -1 ? '?' : '&';
                        onclick += "window.open('" + url + op + "submissionId=" + data[i].submission_id + "', '_blank');";
                        //                            if (i == 0) {
                        //                                color = 'success';
                        //                            }
                        toastr.info(data[i].message + '<br><button class="btn btn-xs btn-primary" onclick="' + onclick + '"><i class="icon md-check"></i> <?= Yii::t('app', 'รับทราบ') ?></button>', '', {
                            iconClass: 'toast-just-text toast-' + color,
                            messageClass: data[i].id,
                            onclick: function() {
                                alertShown(this.messageClass);
                            }
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    dlgError.alert(textStatus + ": " + jqXHR.status + " " + errorThrown + "</br>" + jqXHR.responseText);
                }
            });
        }

        setInterval(function() {
            updateAlertMenu();
            checkUnshownAlert();
        }, <?= Yii::$app->params['alertCheckInterval'] ?>);
    </script>
</body>

</html>
<?php $this->endPage() ?>