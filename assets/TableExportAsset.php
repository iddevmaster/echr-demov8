<?php

namespace app\assets;

use yii\web\AssetBundle;

class TableExportAsset extends AssetBundle {

    public $sourcePath = '@bower';
    public $js = [
        'js-xlsx/dist/xlsx.core.min.js',
        'file-saverjs/FileSaver.min.js',
        'tableexport.js/dist/js/tableexport.min.js',
    ];
    public $publishOptions = [
    ];

}
