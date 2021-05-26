<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use homer\assets\HomerAdminAsset;

AppAsset::register($this);
HomerAdminAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= Yii::getAlias('@web') ?>/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="<?= $class ?>">
<?php $this->beginBody() ?>
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1><?= \Yii::$app->keyStorage->get('app-name', Yii::$app->name); ?></h1><p></p><div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div> </div> </div>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
