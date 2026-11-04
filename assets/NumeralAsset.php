<?php

namespace app\assets;

use yii\web\AssetBundle;

class NumeralAsset extends AssetBundle {

    public $sourcePath = '@npm/numeral';
    public $js = [
        'min/numeral.min.js',
    ];
    public $publishOptions = [
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];

}
