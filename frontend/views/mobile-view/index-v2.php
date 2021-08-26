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
$this->registerJs('var model = '.Json::encode($model).'; ',View::POS_HEAD);
$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
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
.hpanel.hbggreen .panel-body {
    background: #0baabd;
    color: #fff;
    border: none;
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
<div class="container" style="width: auto;">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="hpanel hbggreen" style="padding-top: 25px;">
                <div class="panel-body">
                    <div class="text-center">
                        <?= Html::img(\Yii::$app->keyStorage->get('logo-company', '/img/logo/logoKM4.png'),['width' => '200px','class' => 'img-responsive center-block']); ?>
                        <h2 style="line-height: 0; padding-top: 30px;"><?=\Yii::$app->keyStorage->get('kiosk-caption', Yii::$app->name)?></h2>
                         <h3 style="line-height: 0; padding-top: 30px;">ส่วนงาน: <?= $service['service_name'] ?></h3>
                    </div>

                    <div class="text-left" style="margin-left: 20px;">
                        <div class="row">
                            <div class="col-xs-12">
                            <p style="text-align: center;" class="blink">
                                <span style="font-size: 40px;"><?= $model['q_num'] ?></span>
                            </p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-lg btn-default" style="background-color: #0baabd;color: #fff;">ช่องบริการ</button>
                    </div>
                    <div class="text-center">
                        <h3 id="counter"><?= $countData['countername']; ?></h3>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-lg btn-default" style="background-color: #0baabd;color: #fff;">คิวรอ / wait</button>
                    </div>
                    <div class="text-center">
                        <h1 id="qcount"><?= $countData['count']; ?></h1>
                    </div>
                    <?php if($modelTrans && !empty($modelTrans['checkout_date'])): ?>
                    <div class="text-center">
                        <h3>เสร็จสิ้น</h3>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<audio id="alert">
    <source src="/media/notify.mp3" type="audio/mp3" preload="auto" />
    <source src="/media/notify.ogg" type="audio/ogg" preload="auto" >
</audio>
<?php $this->endBody() ?>
<?php
$this->registerJs(<<<JS
socket
.on('display', (res) => {
    Queue.countQ();
})
.on('register', (res) => {
    Queue.countQ();
});
var elm = $('.hbgblue').find('.panel-body');
var elmtheight = [];
$.each(elm, function( index, value ) {
    elmtheight.push($(this).height());
});

if($(window).width() < 768) {
    $(".panel-body").css("height",(Math.max.apply(Math,elmtheight))+40 );
}else{
    $(".panel-body").css("height",(Math.max.apply(Math,elmtheight)) );
}

Queue = {
    countQ: function(){
        $.ajax({
            method: "POST",
            url: baseUrl + "/mobile-view/count-queue",
            dataType: "json",
            data: model,
            success: function(res){
                $('#qcount').html(res.count);
                if(res.count < 3){
                    $('#alert')[0].play();
                    var blink = setInterval(function() {
                        $(".blink").animate({opacity:0},500,"linear",function(){
                            $(this).animate({opacity:1},500);
                        });
                    }, 1000);
                    setTimeout(function(){ clearInterval(blink); }, 7000);
                }
                if(res.countername != null){
                    $('#counter').html(res.countername);
                }
            },
            error:function(jqXHR, textStatus, errorThrown){
                swal({
                    type: 'error',
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
    }
};
JS
);
?>
</body>
</html>
<?php $this->endPage() ?>