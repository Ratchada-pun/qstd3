<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\icons\Icon;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use homer\assets\HomerAdminAsset;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
HomerAdminAsset::register($this);

$appname = \Yii::$app->keyStorage->get('app-name', Yii::$app->name);
$action = Yii::$app->controller->action->id;
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
<body class="">
<?php $this->beginBody() ?>
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version" style="padding-top: 0px;">
        <?= Html::a(Html::img(Yii::getAlias('@web').\Yii::$app->keyStorage->get('logo-url', '/img/logo/logo.jpg'),['class' => 'img-responsive center-block','width' => '70px']),Url::to(['/site/index']),['style' => 'text-transform: uppercase;']); ?>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary"><?= $appname ?></span>
        </div>
        
        <div class="app-name" style="float: left;font-size: 16pt;margin-top: 10px;"><?= $appname ?></div>

        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <a href="#"><?= $this->title ?></a>
                </li>
                <li class="dropdown">
                    <a href="#"><i class="pe-7s-clock"></i></a>
                </li>
                <li class="dropdown">
                    <div class="clock hidden-md-down" style="padding-right: 20px;">
                        
                        <div class="time" style="font-size: 16pt;margin-top: 12px;">
                            <span class="time__hours"><?= date('H') ?></span> :
                            <span class="time__min"><?= date('i') ?></span> :
                            <span class="time__sec"><?= date('s') ?></span>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
<div class="row center-ticket">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>
<div class="footer-kiosk">
    <marquee id="marquee" direction="left">
        <?= \Yii::$app->keyStorage->get('text-marquee-kiosk', '') ?>
    </marquee>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
