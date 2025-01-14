<?php

namespace proger0014\yii2;

use proger0014\yii2\model\ScheduleModel;
use yii\base\InvalidArgumentException;
use yii\bootstrap\InputWidget;

class ScheduleInputWidget extends InputWidget
{
    public bool $enableTimeZone = true;
    public bool $enableProductionCalendar = true;
    public bool $enableSpecialTime = true;
    public bool $allowMultipleItems = true;


    public function init() {
        parent::init();

        if ($this->model) {
            $this->checkRequirements();
            $this->configureAttributes();
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function checkRequirements(): void {
        $model = $this->model[$this->attribute];

        if (!$model instanceof ScheduleModel
            || !is_subclass_of($model, ScheduleModel::class, false)) {
            throw new InvalidArgumentException("model in attribute[$this->attribute] must be of ScheduleModel or extends from that");
        }
    }

    public function configureAttributes(): void {
        $attributes = $this->model->attributes();

        if (!$this->enableTimeZone) {
            unset($attributes['enableTimeZone']);
        }

        if (!$this->enableProductionCalendar) {
            unset($attributes['enableProductionCalendar']);
        }

        if (!$this->enableSpecialTime) {
            unset($attributes['enableSpecialTime']);
        }

        if (!$this->allowMultipleItems) {
            unset($attributes['allowMultipleItems']);
        }

        $this->model->attributes = $attributes;
    }
}