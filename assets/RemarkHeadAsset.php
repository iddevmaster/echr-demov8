<?php

namespace app\assets;

use yii\web\AssetBundle;

class RemarkHeadAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $js = [
        'remark/global/vendor/modernizr/modernizr.min.js',
        'remark/global/vendor/breakpoints/breakpoints.min.js',
    ];
    public $depends = [
    ];

}
