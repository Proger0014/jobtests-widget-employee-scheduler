<?php

namespace proger0014\yii2\model;

use proger0014\yii2\MappingAble;
use yii\base\Model;

abstract class BaseTime extends Model implements MappingAble {
    public \DateTime $startTime;
    public \DateTime $endTime;

    public function mapping(): array {
        return [
            'startTime' => 'start_time',
            'endTime' => 'end_time'
        ];
    }
}