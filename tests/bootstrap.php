<?php

$consts = require dirname(__DIR__) . '/consts.php';

error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

Yii::setAlias($consts['alias_tests']['name'], $consts['alias_tests']['path']);
Yii::setAlias($consts['alias_src']['name'], $consts['alias_src']['path']);