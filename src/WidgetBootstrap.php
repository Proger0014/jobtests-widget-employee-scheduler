<?php

namespace proger0014\yii2;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

class WidgetBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $consts = require dirname(__DIR__) . '/consts.php';

        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) use ($consts) {
            Yii::setAlias($consts['alias_assets']['name'], $consts['alias_assets']['path']);
        });
    }
}