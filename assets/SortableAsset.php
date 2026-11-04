<?php

namespace app\assets;

use yii\web\AssetBundle;

class SortableAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'remark/global/vendor/html5sortable/sortable.css',
    ];
    public $js = [
        'remark/global/vendor/html5sortable/html.sortable.min.js',
        'remark/global/js/components/html5sortable.js'
    ];
    public $depends = [
        'app\assets\RemarkAsset'
    ];

}
