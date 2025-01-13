<?php

namespace proger0014\yii2;

use yii\bootstrap\BootstrapAsset;
use yii\bootstrap\BootstrapPluginAsset;
use yii\web\AssetBundle;

class WidgetAsset extends AssetBundle
{
    public $sourcePath = "@proger0014/assets";

    public $depends = [
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
    ];
}