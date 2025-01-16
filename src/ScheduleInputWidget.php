<?php

namespace proger0014\yii2;

use proger0014\yii2\model\ScheduleModel;
use yii\base\InvalidArgumentException;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;

class ScheduleInputWidget extends InputWidget
{
    public bool $enableTimeZone = true;
    public bool $enableProductionCalendar = true;
    public bool $enableSpecialTime = true;
    public bool $allowMultipleItems = true;


    public function init() {
        parent::init();

        if ($this->model) {
            $this->checkRequirementsModel();
            $this->configureAttributes();
        } else {
            $this->checkRequirements();
        }
    }

    public function getBaseName(): string {
        if (!$this->model) {
            return $this->name;
        }

        return $this->model->formName() . "[$this->attribute]";
    }

    public function run(): string {
        WidgetAsset::register($this->view);

        $render = Html::beginTag('div', ['class' => 'widget panel panel-default']);
        $render .= Html::beginTag('div', ['class' => 'panel-body']);

        $render .= Html::tag('p', 'Рабочие часы', ['class' => 'title-1']);
        $render .= Html::tag('p', 'Установить рабочие часы');
        $render .= Html::tag('hr', null, ['class' => 'mb-25']);

        $render .= $this->renderSwitchSection('Учитывать часовой пояс', 'icon', $this->enableTimeZone);
        $render .= $this->renderSwitchSection('Производственный календарь', 'icon', $this->enableProductionCalendar);

        $render .= $this->renderTimeSection();

        $render .= Html::endTag('div');
        return $render . Html::endTag('div');
    }

    private function renderTimeSection(): string {
        $render = Html::beginTag('div', ['class' => 'time-list mb-25']);

        $render .= Html::beginTag('div', ['class' => 'item panel panel-default border']);
        $render .= Html::beginTag('div', ['class' => 'panel-body']);
        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb ai-center']);

        $render .= Html::beginTag('div', ['class' => 'd-flex']);
        $render .= $this->renderDayInput('Пн', 0);
        $render .= $this->renderDayInput('Вт', 1);
        $render .= $this->renderDayInput('Ср', 2);
        $render .= $this->renderDayInput('Чт', 3);
        $render .= $this->renderDayInput('Пт', 4);
        $render .= $this->renderDayInput('Сб', 5);
        $render .= $this->renderDayInput('Вс', 6);
        $render .= Html::endTag('div');

        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb ai-center']);

        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb']);
        $render .= $this->renderTimeInput();
        $render .= Html::beginTag('div', ['class' => 'd-flex jc-center ai-center mx-5']);
        $render .= Html::tag('hr', null, ['class' => 'straight']);
        $render .= Html::endTag('div');
        $render .= $this->renderTimeInput();
        $render .= Html::endTag('div');

        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb']);
        $render .= $this->renderIconButton('1');
        $render .= $this->renderIconButton('2', false);
        $render .= Html::endTag('div');

        $render .= Html::endTag('div');


        $render .= Html::endTag('div');
        $render .= Html::endTag('div');
        $render .= Html::endTag('div');

        return $render . Html::endTag('div');
    }

    private function renderIconButton($icon, bool $withMr = true): string {
        $class = 'btn btn-icon';

        if ($withMr) {
            $class .= ' mr-8';
        }

        return Html::tag('button', $icon, ['class' => $class]);
    }

    private function renderTimeInput(): string {
        $render = Html::beginTag('div', ['class' => 'form-group']);
        $render .= Html::input('time', null, '00:00', ['min' => '00:00', 'max' => '23:59']);
        return $render . Html::endTag('div');
    }

    private function renderDayInput(string $content, int $index): string {
        $render = Html::beginTag('label', ['class' => 'day']);

        $render .= Html::checkbox($this->getBaseName() . "[work_time][$index][day]");
        $render .= Html::tag('span', $content);

        return $render . Html::endTag('label');
    }

    private function renderSwitchSection(string $label, $icon, bool $enabled): string {
        $render = Html::beginTag('div', ['class' => 'd-flex jc-sb']);

        $render .= Html::beginTag('div');
        $render .= Html::tag('label', $label);
        // TODO icon
        $render .= Html::tag('span', $icon);
        $render .= Html::endTag('div');

        if ($enabled) {
            $render .= $this->renderSwitch();
        }

        return $render . Html::endTag('div');
    }

    private function renderSwitch(): string {
        return Html::checkbox('', false, [
            'form' => ''
        ]);
    }

    private function getModel(): ScheduleModel {
        return $this->model[$this->attribute];
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function checkRequirementsModel(): void {
        $model = $this->model[$this->attribute];

        if (!$model instanceof ScheduleModel
            || !is_subclass_of($model, ScheduleModel::class, false)) {
            throw new InvalidArgumentException("model in attribute[$this->attribute] must be of ScheduleModel or extends from that");
        }
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function checkRequirements(): void {
        if (!$this->name) {
            throw new InvalidArgumentException("name is required");
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