<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use frontend\assets\AppAsset;
use homer\assets\HomerAdminAsset;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;
use homer\assets\SweetAlert2Asset;

AppAsset::register($this);
HomerAdminAsset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);
SweetAlert2Asset::register($this);

$this->title = \Yii::$app->keyStorage->get('app-name', Yii::$app->name);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="boxed">
<?php $this->beginBody() ?>
<style type="text/css">
.hpanel .panel-body{
    padding: 2px;
}
@media (max-width: 768px) {
    h1 {
        font-size: 30px;
    }
}
@media (max-width: 768px) {
    h3 {
        font-size: 18px;
    }
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="hpanel hbgred" style="padding-top: 20px;">
                <div class="panel-body">
                    <div class="text-center">
                        <p class="text-big font-light">ไม่พบข้อมูลคิว</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>