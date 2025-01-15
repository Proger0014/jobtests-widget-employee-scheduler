<?php

namespace proger0014\yii2\model;

use proger0014\yii2\MappingAble;
use yii\base\Model;

class ScheduleModel extends Model implements MappingAble {
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

    /**
     * use for mapping form structures into model
     */
    public function mapping(): array {
        return [
            'workTime' => 'work_time',
            'specialTime' => 'special_time',
            'enableTimeZone' => 'enable_time_zone',
            'enableProductionCalendar' => 'enable_production_calendar'
        ];
    }

    public function rules(): array {
        return [
            [['name'], 'required'],
            [['enableTimeZone', 'enableProductionCalendar', 'enableSpecialTime'], 'boolean']
        ];
    }
}