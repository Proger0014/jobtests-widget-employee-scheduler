<?php

namespace proger0014\yii2;

use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

class WidgetBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->on(Application::EVENT_BEFORE_REQUEST, function ($event) {
            Yii::setAlias('@proger0014/assets', dirname(__DIR__) . '/assets');
        });
    }
}