<?php

namespace proger0014\yii2\model;

use yii\base\Model;

abstract class BaseTime extends Model {
    public \DateTime $startTime;
    public \DateTime $endTime;
}