<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\icons\Icon;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use homer\assets\HomerAdminAsset;

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
<?php if($action == 'display-list'): ?>
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version">
        <?= Html::a($appname,Url::to(['/site/index']),['style' => 'text-transform: uppercase;']); ?>
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary"><?= $appname ?></span>
        </div>
        <div class="mobile-menu">
            <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="collapse mobile-navbar" id="mobile-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <?= Html::a('โปรแกรมเสียง',['/kiosk/calling/play-sound'],['title' => 'Sound']); ?>
                    </li>
                    <li>
                        <?= Html::a('เข้าสู่ระบบ',['/user/security/login'],['title' => 'Login']); ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li class="dropdown">
                    <?= Html::a('<i class="pe-7s-speaker"></i>',['/kiosk/calling/play-sound']); ?>
                </li>
                <li class="dropdown">
                    <?= Html::a('<i class="pe-7s-monitor"></i>',['/kiosk/display/display-list']); ?>
                </li>
                <?php if(Yii::$app->user->isGuest):?>
                    <li class="dropdown">
                        <?= Html::a(Icon::show('sign-in').'เข้าสู่ระบบ',['/user/security/login'],['title' => 'Sign In']); ?>
                    </li>
                <?php endif; ?>
                <?php if(!Yii::$app->user->isGuest):?>
                    <li class="dropdown">
                        <?= Html::a(Icon::show('home').'หน้าหลัก',['/site/index'],['title' => 'หน้าหลัก']); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>
<?php else: ?>
<!-- Header -->
<div class="color-line">
<?php endif; ?>
<div class="splash"> <div class="color-line"></div><div class="splash-title"><h1><?= \Yii::$app->keyStorage->get('app-name', Yii::$app->name); ?></h1><p></p><div class="spinner"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div> </div> </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
