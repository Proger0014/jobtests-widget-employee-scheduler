# Планировщик рабочего времени

Тестовое задание на тему разработки виджета yii2 планирования рабочего времени

## Требования

### внешние зависимости

- jquery 3.4.1
- bootstrap 3.4.1

### api виджета

#### без модели

```php
<?= \proger0014\yii2\ScheduleInputWidget::widget(['name' => 'schedule']); ?>
```

#### использование с моделью

```php
<?php $form = \yii\widgets\ActiveForm::begin(); ?>

<?= $form->field($model, 'schedule')
    ->widget(\proger0014\yii2\ScheduleInputWidget::class); ?>

<?php \yii\widgets\ActiveForm::end(); ?>
```

#### конфигурация виджета

```php
[
    'name'* => string,
    'enableTimeZone' => bool,
    'enableSpecialTime' => bool,
    'enableProductionCalendar' => bool,
    'allowMultipleItems' => bool
]
```

| поле                     | описание                                                             | значение <br/>по умолчанию |
|--------------------------|----------------------------------------------------------------------|----------------------------|
| name                     | требуется для того, чтобы у name полей виджета был начальный префикс | -                          |
| enableTimeZone           | отображает чек бокс учитывания часового пояса                        | true                       |
| enableProductionCalendar | отображает чек бокс производственного календаря                      | true                       |
| enableSpecialTime        | отображает кнопку "Добавить особенные дни"                           | true                       |
| allowMultipleItems       | отображает кнопку "Добавить рабочие часы"                            | true                       |

### структура данных

```json
{
  "schedule": {
    "work_time": [
      {
        "day": 1,
        "start_time": "H:i:s",
        "end_time": "H:i:s"
      }
    ],
    "special_time": [
      {
        "start_time": "Y-m-d H:i:s",
        "end_time": "Y-m-d H:i:s"
      }
    ],
    "enable_time_zone": "0|1",
    "enable_production_calendar": "0|1"
  }
}
```

## Мануал

### установка

Сначала нужно добавить репозиторий в `composer.json` файл:

```json
{
  ...
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/Proger0014/jobtests-widget-employee-scheduler.git"
    }
  ],
  ...
}
```

затем скачать зависимость:

```cmd
composer require "proger0014/jobtest-widget-scheduler:1.0.2-BETA"
```

**Возможные проблемы**

- Конфликт зависимостей, связанный с `yii2-bootstrap`, который конфликтует с `yii2-bootstrap5`

### использование

если создается форма, модель которой имеет поле, связанного с `ScheduleInputWidget`, поле должно быть либо экземпляром `ScheduleModel`, либо наследником `ScheduleModel`

*пример создания формы*

```php
// В методе контроллера
$formModel = new FormModel([
    'attribute' => new ScheduleModel()
]);

return $this->render('view', [
    'formModel' => $formModel
])

// В представлении
<?php $form = yii\widgets\ActiveForm::begin(); ?>

<?= $form->field($formModel, 'attribute')->widget(ScheduleInputWidget::class); ?>

<div>
    <button type="submit">отправить</button>
</div>

<?php \yii\widgets\ActiveForm::end(); ?>

```

## Чего нет

- нет работы с особенными днями
- нет подтверждающих диалогов при попытке удалить правило
- нет валидации
- нет pixel perfect верстки
- нет конвертера структуры пост запроса в модель