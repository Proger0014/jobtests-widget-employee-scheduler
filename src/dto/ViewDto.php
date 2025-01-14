<?php

namespace proger0014\yii2\dto;

use yii\base\Model;

class ViewDto extends Model {
    public bool $enableTimeZone;
    public bool $enableProductionCalendar;
    public bool $enableSpecialTime;
    public bool $allowMultipleItems;
}