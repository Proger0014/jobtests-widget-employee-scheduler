<?php

namespace proger0014\yii2\model;

use yii\base\Model;

class ScheduleModel extends Model
{
    /**
     * @var WorkTime[]
     */
    public array $workTime;

    /**
     * @var SpecialTime[]
     */
    public array $specialTime;

    public int $enableTimeZone;

    public int $enableProductionCalendar;
}