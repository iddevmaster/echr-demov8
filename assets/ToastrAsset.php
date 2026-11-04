<?php

namespace app\assets;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'remark/global/vendor/toastr/toastr.min.css',
    ];
    public $js = [
        'remark/global/vendor/toastr/toastr.min.js',
        'remark/global/js/components/toastr.min.js',
    ];
    public $depends = [
        'app\assets\RemarkAsset'
    ];

}
