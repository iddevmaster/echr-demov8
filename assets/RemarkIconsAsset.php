<?php

namespace app\assets;

use yii\web\AssetBundle;

class RemarkIconsAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'remark/global/fonts/font-awesome/font-awesome.min.css',
        'remark/global/fonts/glyphicons/glyphicons.min.css',
        'remark/global/fonts/ionicons/ionicons.min.css',
    ];
    public $js = [
    ];
    public $depends = [
        'app\assets\RemarkAsset'
    ];

}
