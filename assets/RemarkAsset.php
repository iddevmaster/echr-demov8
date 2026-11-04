<?php

namespace app\assets;

use yii\web\AssetBundle;

class RemarkAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // Bootstrap and site stylesheets
//        YII_ENV_DEV ? 'remark/global/css/bootstrap.css' : 'remark/global/css/bootstrap.min.css',
        'remark/global/css/bootstrap-extend.min.css',
        'remark/center/assets/css/site.min.css',
        // Necessary plugin stylesheets
        'remark/global/vendor/animsition/animsition.min.css',
        'remark/global/vendor/asscrollable/asScrollable.min.css',
        'remark/global/vendor/switchery/switchery.min.css',
        'remark/global/vendor/icheck/icheck.min.css',
        'remark/global/vendor/slidepanel/slidePanel.min.css',
        'remark/global/vendor/flag-icon-css/flag-icon.min.css',
      //  'remark/global/vendor/toolbar/toolbar.min.css',
        // Icon stylesheets
        'remark/global/fonts/web-icons/web-icons.min.css',
        'remark/global/fonts/brand-icons/brand-icons.min.css',
//        'http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic',
        // others
        'remark/global/fonts/material-design/material-design.min.css',
        'remark/global/vendor/waves/waves.min.css',
        'remark/global/vendor/select2/select2.min.css',
        'remark/center/assets/skins/kku.min.css',
//        YII_ENV_DEV ? 'remark/global/vendor/intro-js/introjs.css' : 'remark/global/vendor/intro-js/introjs.min.css',
//        YII_ENV_DEV ? 'remark/global/vendor/chartist-js/chartist.css' : 'remark/global/vendor/chartist-js/chartist.min.css',
//        YII_ENV_DEV ? 'remark/global/vendor/jvectormap/jquery-jvectormap.css' : 'remark/global/vendor/jvectormap/jquery-jvectormap.min.css',
//        YII_ENV_DEV ? 'remark/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css' : 'remark/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.css',
//        YII_ENV_DEV ? 'remark/global/vendor/gridstack/gridstack.css' : 'remark/global/vendor/gridstack/gridstack.min.css',
    ];
    public $js = [
        // Core and plugin dependencies
//        YII_ENV_DEV ? 'remark/global/vendor/jquery/jquery.js' : 'remark/global/vendor/jquery/jquery.min.js',
//        YII_ENV_DEV ? 'remark/global/vendor/bootstrap/bootstrap.js' : 'remark/global/vendor/bootstrap/bootstrap.min.js',
        'remark/global/vendor/animsition/animsition.min.js',
        'remark/global/vendor/asscroll/jquery-asScroll.min.js',
        'remark/global/vendor/mousewheel/jquery.mousewheel.min.js',
        'remark/global/vendor/asscrollable/jquery.asScrollable.all.min.js',
        'remark/global/vendor/ashoverscroll/jquery-asHoverScroll.min.js',
        'remark/global/vendor/switchery/switchery.min.js',
        'remark/global/vendor/icheck/icheck.min.js',
        'remark/global/vendor/screenfull/screenfull.min.js',
        'remark/global/vendor/slidepanel/jquery-slidePanel.min.js',
       // 'remark/global/vendor/toolbar/jquery.toolbar.min.js',
        // Template relating scripts
        'remark/global/js/core.min.js',
        'remark/center/assets/js/site.min.js',
        'remark/center/assets/js/sections/menu.min.js',
        'remark/center/assets/js/sections/menubar.min.js',
        'remark/center/assets/js/sections/sidebar.min.js',
        // Template config scripts
        'remark/global/js/configs/config-colors.min.js',
        'remark/global/js/components/asscrollable.min.js',
        'remark/global/js/components/animsition.min.js',
        'remark/global/js/components/slidepanel.min.js',
        'remark/global/js/components/switchery.min.js',
        'remark/global/js/components/icheck.min.js',
        // Others
        'remark/global/vendor/waves/waves.min.js',
        'remark/global/vendor/jquery-placeholder/jquery.placeholder.min.js',
        'remark/global/js/components/buttons.min.js',
        'remark/global/js/components/material.min.js',
        'remark/global/js/components/gridstack.min.js',
        'remark/global/js/components/jquery-placeholder.min.js',
        'remark/center/assets/examples/js/uikit/button.min.js',
//        YII_ENV_DEV ? 'remark/global/vendor/intro-js/intro.js' : 'remark/global/vendor/intro-js/intro.min.js',
//        YII_ENV_DEV ? 'remark/global/vendor/lodash/lodash.js' : 'remark/global/vendor/lodash/lodash.min.js',
//        YII_ENV_DEV ? 'remark/global/vendor/jquery-ui/jquery-ui.js' : 'remark/global/vendor/jquery-ui/jquery-ui.min.js',
//        YII_ENV_DEV ? 'remark/global/vendor/gridstack/gridstack.js' : 'remark/global/vendor/gridstack/gridstack.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\RemarkHeadAsset',
    ];

}
