<?php

namespace app\assets;

use yii\web\AssetBundle;

class HotkeysAsset extends AssetBundle {

    public $sourcePath = '@bower/hotkeysjs';
    public $js = [
        'dist/hotkeys.min.js',
    ];
    public $publishOptions = [
    ];

}
