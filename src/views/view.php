<?php
/** @var proger0014\yii2\dto\ViewDto $dto */
?>

<div class="widget panel panel-default">
    <div class="panel-body">
        <p class="title-1">Рабочие часы</p>

        <p>Установить рабочие часы</p>

        <hr>

        <?php if ($dto->enableTimeZone): ?>
        <div class="d-flex jc-sb">
            <div>
                <label>Учитывать часовой пояс</label>
                <span>icon</span>
            </div>
            <input type="checkbox">
        </div>
        <?php endif; ?>

        <?php if ($dto->enableProductionCalendar): ?>
        <div class="d-flex jc-sb">
            <div>
                <label>Производственный календарь</label>
                <span>icon</span>
            </div>
            <input type="checkbox">
        </div>
        <?php endif; ?>

        
    </div>
</div>
