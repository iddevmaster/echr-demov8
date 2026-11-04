<?php

namespace app\assets;

use yii\web\AssetBundle;

class TabsAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [

    ];
    public $js = [
        'remark/global/js/components/tabs.js',
        'remark/global/js/plugins/responsive-tabs.js',
        'remark/global/js/plugins/closeable-tabs.js',
    ];
    public $depends = [
        'app\assets\RemarkAsset'
    ];

}
