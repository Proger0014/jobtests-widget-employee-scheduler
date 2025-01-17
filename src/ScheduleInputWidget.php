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

    // TODO: update tags for js
    public function run(): string {
        WidgetAsset::register($this->view);

        $id = uniqid('widget-');

        $this->view->registerJs('$("#' . $id . '").registerWidget();');

        $containerOptions = ['class' => 'widget panel panel-default', 'id' => $id];

        if ($this->field) {
            $containerOptions['widget-target'] = $this->field->form->options['id'];
        }

        $render = Html::beginTag('div', $containerOptions);
        $render .= Html::beginTag('div', ['class' => 'panel-body']);

        $render .= Html::tag('p', 'Рабочие часы', ['class' => 'title-1']);
        $render .= Html::tag('p', 'Установить рабочие часы');
        $render .= Html::tag('hr', null, ['class' => 'mb-25']);

        $render .= $this->renderSwitchSection('Учитывать часовой пояс', $this->getBaseName() . '[enable_time_zone]', $this->enableTimeZone);
        $render .= $this->renderSwitchSection('Производственный календарь', $this->getBaseName() . '[enable_production_calendar]', $this->enableProductionCalendar);

        $render .= $this->renderTimeSection();

        $render .= $this->renderButtons();

        $render .= Html::endTag('div');
        return $render . Html::endTag('div');
    }

    private function renderTimeSection(): string {
        $render = Html::beginTag('div', ['class' => 'time-list mb-25']);

        $render .= Html::beginTag('div', ['class' => 'item panel panel-default border']);
        $render .= Html::beginTag('div', ['class' => 'panel-body']);
        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb ai-center']);

        $render .= Html::beginTag('div', ['class' => 'd-flex']);

        $startTimeName = 'start_time';
        $endTimeName = 'end_time';

        $binds = [
            $startTimeName . '(' . $this->getBaseName() . "[work_time][{index}][$startTimeName])",
            $endTimeName . '(' . $this->getBaseName() . "[work_time][{index}][$endTimeName])"
        ];

        $render .= $this->renderDayInput('Пн', 0, $binds);
        $render .= $this->renderDayInput('Вт', 1, $binds);
        $render .= $this->renderDayInput('Ср', 2, $binds);
        $render .= $this->renderDayInput('Чт', 3, $binds);
        $render .= $this->renderDayInput('Пт', 4, $binds);
        $render .= $this->renderDayInput('Сб', 5, $binds);
        $render .= $this->renderDayInput('Вс', 6,$binds);
        $render .= Html::endTag('div');

        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb ai-center']);

        $render .= Html::beginTag('div', ['class' => 'd-flex jc-sb']);
        $render .= $this->renderTimeInput($startTimeName);
        $render .= Html::beginTag('div', ['class' => 'd-flex jc-center ai-center mx-5']);
        $render .= Html::tag('hr', null, ['class' => 'straight']);
        $render .= Html::endTag('div');
        $render .= $this->renderTimeInput($endTimeName);
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

    private function renderButtons(): string {
        if (!$this->enableSpecialTime && !$this->allowMultipleItems) {
            return '';
        }

        $render = Html::beginTag('div', ['class' => 'd-flex jc-sb mt-25']);

        if ($this->enableSpecialTime) {
            $render .= Html::tag('button', 'Добавить особенные дни', ['class' => 'btn btn-default', 'type' => 'button']);
        }

        if ($this->allowMultipleItems) {
            $render .= Html::tag('button', 'Добавить рабочие часы', ['class' => 'btn btn-primary', 'type' => 'button']);
        }

        return $render . Html::endTag('div');
    }

    private function renderIconButton($icon, bool $withMr = true): string {
        $class = 'btn btn-icon';

        if ($withMr) {
            $class .= ' mr-8';
        }

        return Html::tag('button', $icon, ['class' => $class, 'type' => 'button']);
    }

    private function renderTimeInput(string $name): string {
        $render = Html::beginTag('div', ['class' => 'form-group', 'input-container']);
        $render .= Html::input('time', null, '00:00', [
            'min' => '00:00',
            'max' => '23:59',
            'class' => 'form-control',
            'widget-input-type' => 'time',
            'widget-input-name' => $name]);
        return $render . Html::endTag('div');
    }

    private function renderDayInput(string $content, int $index, array $binds): string {
        $render = Html::beginTag('label', ['class' => 'day form-group', 'input-container']);

        $bindsStr = trim(array_reduce($binds, function($total, $bind) use ($index) {
            $bindFormatted = str_replace('{index}', $index, $bind);

            $total .= ' ' . $bindFormatted;

            return $total;
        }, ''));

        $render .= Html::checkbox(null, false, [
            'widget-input-type' => 'switch-day-of-week',
            'widget-input-binds' => $bindsStr
        ]);
        $render .= Html::tag('span', $content);

        return $render . Html::endTag('label');
    }

    private function renderSwitchSection(string $label, string $name, bool $enabled): string {
        $render = Html::beginTag('div', ['class' => 'd-flex jc-sb']);

        $render .= Html::beginTag('div');
        $render .= Html::tag('label', $label);
        // TODO icon
        $render .= Html::tag('span', 'icon');
        $render .= Html::endTag('div');

        if ($enabled) {
            $render .= $this->renderSwitch($name);
        }

        return $render . Html::endTag('div');
    }

    private function renderSwitch($name): string {
        return Html::checkbox(null, false, [
            'widget-input-type' => 'switch',
            'widget-input-name' => $name
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