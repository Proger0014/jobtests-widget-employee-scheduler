<?php

namespace proger0014\yii2;

use yii\bootstrap\Widget;

class ScheduleInputWidget extends Widget
{
    public string $name;
    public bool $enableTimeZone = true;
    public bool $enableProductionCalendar = true;
    public bool $enableSpecialTime = true;
    public bool $allowMultipleItems = true;

    public function rules(): array {
        return [
            [['name'], 'required']
        ];
    }
}