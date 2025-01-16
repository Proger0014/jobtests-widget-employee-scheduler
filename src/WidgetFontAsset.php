<?php

namespace proger0014\yii2;

use yii\web\AssetBundle;

class WidgetFontAsset extends AssetBundle {
    public $sourcePath = '@npm/fontsource--roboto';

    public $css = [
        '400.css',
        '700.css',
        '800.css'
    ];
}