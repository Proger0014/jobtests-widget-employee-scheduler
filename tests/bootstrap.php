<?php

error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@proger0014/tests/yii2', __DIR__);
Yii::setAlias('@proger0014/yii2', dirname(__DIR__) . '/src');