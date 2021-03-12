<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\icons\Icon;

$appname = \Yii::$app->keyStorage->get('app-name', Yii::$app->name);
?>
<!-- Header -->
<div id="header">
    <div class="color-line">
    </div>
    <div id="logo" class="light-version" style="padding:12px ;">
        <?= Html::a(Html::img(Yii::getAlias('@web').\Yii::$app->keyStorage->get('logo-url', '/img/logo/logoKM4.png'),['class' => 'img-responsive center-block','width' => '100%',]),Url::to(['/site/index']),['style' => 'text-transform: uppercase']); ?>
        <!-- <span>
            Homer Theme
        </span> -->
    </div>
    <nav role="navigation">
        <div class="header-link hide-menu"><i class="fa fa-bars"></i></div>
        <div class="small-logo">
            <span class="text-primary"><?= $appname ?></span>
        </div>
        <div class="app-name" style="float: left;font-size: 16pt;margin-top: 10px;"><?= $appname ?></div>
        <div class="mobile-menu">
            <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                <i class="fa fa-chevron-down"></i>
            </button>
            <div class="collapse mobile-navbar" id="mobile-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <?= Html::a(Icon::show('home').'หน้าหลัก',['/'],['title' => 'หน้าหลัก','data-pjax' => '0']); ?>
                    </li>
                    <li>
                        <?= Html::a(Icon::show('dashboard').'แดชบอร์ด',['/dashboard'],['title' => 'Dashboard']); ?>
                    </li>
                   
                    <?php if(!Yii::$app->user->isGuest):?>
                    <li>
                        <?= Html::a(Icon::show('newspaper-o').'ข้อมูลส่วนตัว',['/user/settings/profile'],['title' => 'ข้อมูลส่วนตัว','data-pjax' => '0']); ?>
                    </li>
                    <li>
                        <?= Html::a(Icon::show('sign-out').'ออกจากระบบ',['/user/security/logout'],['title' => 'ออกจากระบบ','data-method' => 'post']); ?>
                    </li>
                    <?php endif; ?>
                    <?php if(Yii::$app->user->isGuest):?>
                    <li>
                        <?= Html::a(Icon::show('pe-7s-upload pe-rotate-90', [], Icon::I).'เข้าสู่ระบบ',['/user/security/login'],['title' => 'Login']); ?>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="navbar-right">
            <ul class="nav navbar-nav no-borders">
                <li>
                    <div class="clock hidden-md-down">
                        <div class="time" style="font-size: 16pt;margin-top: 12px;">
                            <span class="time__hours"><?= date('H') ?></span> :
                            <span class="time__min"><?= date('i') ?></span> :
                            <span class="time__sec"><?= date('s') ?></span>
                        </div>
                    </div>
                </li>
                <li class="dropdown">
                    <?= Html::a('<i class="fa fa-dashboard"></i>',['/site/index'],['title' => 'Dashboard']); ?>
                </li>
                
              
                <?php if(!Yii::$app->user->isGuest):?>
                <li>
                    <a href="#" id="sidebar" class="right-sidebar-toggle">
                        <i class="pe-7s-config"></i>
                    </a>
                </li>
                <li class="dropdown">
                    <?= Html::a(Icon::show('sign-out'),['/user/security/logout'],['title' => 'Sign Out','data-method' => 'post']); ?>
                </li>
                <?php endif; ?>
                <?php if(Yii::$app->user->isGuest):?>
                    <li class="dropdown">
                        <?= Html::a(Icon::show('sign-in').'เข้าสู่ระบบ',['/user/security/login'],['title' => 'Sign In']); ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>