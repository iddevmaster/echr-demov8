<?php

namespace app\assets;

use yii\web\AssetBundle;

class ToolbarJsAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'remark/global/vendor/toolbar/toolbar.css',
        'remark/global/vendor/jquery-wizard/jquery-wizard.css',
        'remark/global/vendor/formvalidation/formValidation.css',
    ];
    public $js = [
        'remark/global/vendor/toolbar/jquery.toolbar.min.js',
        'remark/global/js/components/toolbar.js',
        'remark/global/js/components/tabs.js',
        'remark/global/vendor/jquery-wizard/jquery-wizard.js',
        'remark/global/js/components/jquery-wizard.js',
        'remark/center/assets/examples/js/forms/wizard.js',
        'remark/global/vendor/formvalidation/formValidation.js',
        'remark/global/js/components/matchheight.js',
        'remark/global/vendor/matchheight/jquery.matchHeight-min.js',
    ];
    public $depends = [
        'app\assets\RemarkAsset'
    ];

}
