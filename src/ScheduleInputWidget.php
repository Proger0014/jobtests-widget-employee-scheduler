<?php

namespace proger0014\yii2;

use proger0014\yii2\model\ScheduleModel;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;
use yii\helpers\StringHelper;

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

    public function run(): string {
        $render = Html::beginTag('div', ['class' => 'widget panel panel-default']);
        $render .= Html::beginTag('div', ['class' => 'panel-body']);

        $render .= Html::tag('p', 'Рабочие часы', ['class' => 'title-1']);
        $render .= Html::tag('p', 'Установить рабочие часы');
        $render .= Html::tag('hr', null, ['class' => 'mb-25']);

        $render .= $this->renderSwitchSection('Учитывать часовой пояс', 'icon', $this->enableTimeZone);
        $render .= $this->renderSwitchSection('Производственный календарь', 'icon', $this->enableProductionCalendar);

        $render .= Html::endTag('div');
        return $render . Html::endTag('div');
    }

    // TODO name of input
    private function renderSwitchSection(string $label, $icon, bool $enabled): string {
        $render = Html::beginTag('div', $this->getWrapperOptions(['class' => 'd-flex jc-sb'], $enabled));

        $render .= Html::beginTag('div');
        $render .= Html::tag('label', $label);
        // TODO icon
        $render .= Html::tag('span', $icon);
        $render .= Html::endTag('div');

        $render .= $this->renderSwitch('switch');

        return $render . Html::endTag('div');
    }

    // TODO name
    private function renderSwitch(string $name): string {
        return Html::checkbox($name);
    }

    private function getWrapperOptions(array $src, bool $enabled): array {
        $enabledClass = $enabled ? '' : 'disabled';

        Html::addCssClass($src, $enabledClass);

        return $src;
    }

    private function getModel(): ScheduleModel {
        return $this->model[$this->attribute];
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
        $model = $this->getModel();
        $attributes = $model->attributes();

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

        $model->attributes = $attributes;
    }
}