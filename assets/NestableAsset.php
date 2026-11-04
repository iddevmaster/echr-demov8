<?php

namespace app\assets;

use yii\web\AssetBundle;

class NestableAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'remark/global/vendor/nestable/nestable.css',
//        'https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.css'
    ];
    public $js = [
//        'remark/global/vendor/nestable/jquery.nestable.js',
//        'remark/global/js/components/nestable.js'
        'https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js'
    ];
    public $depends = [
        'app\assets\RemarkAsset'
    ];

}
