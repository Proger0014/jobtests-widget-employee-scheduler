<?php

namespace proger0014\yii2;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = "@proger0014/assets";

    public $css = [
        'proger0014.widget.css'
    ];

    public $js = [
        'proger0014.widget.jquery.js'
    ];

    public $depends = [
        WidgetFontAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class
    ];
}