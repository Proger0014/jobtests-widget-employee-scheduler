<?php

/** @var yii\web\View $this */

use app\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\Html;

/** @var string $content */

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="mh-100vh">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="mh-100vh d-f fd-col">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" role="main" class="fg-1">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="my-25px">
    <div class="container">
        <div class="d-f jc-sb text-muted">
            <div>&copy; proger0014 <?= date('Y') ?></div>
            <div><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
