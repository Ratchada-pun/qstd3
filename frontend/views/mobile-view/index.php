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
@media (max-width: 768px) {
    h1 {
        font-size: 30px;
    }
}
</style>
    <div class="container">
        <div class="jumbotron" style="padding-top: 0px;">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                    <?= Html::img(Yii::getAlias('@web').'/img/logo/logo.jpg',['width' => '100px','class' => 'img-responsive center-block']); ?>
                    <h4>โรงพยาบาลชัยนาทนเรนทร</h4>
                    <h4>แผนกอายุรกรรม</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 text-center">
                    <h3><i class="pe-7s-id"></i> <?= $model['pt_name'] ?></h3>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 text-center">
                    <h3 style="font-weight: 500;"> HN : <?= $model['q_hn'] ?></h3>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 text-center">
                    <div class="hpanel hbgblue">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3><i class="fa fa-hashtag"></i> หมายเลขคิว</h3>
                                <h1><?= $model['q_num'] ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 text-center">
                    <div class="hpanel hbgblue">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3><i class="fa fa-hashtag"></i> ห้องตรวจ/ช่องบริการ</h3>
                                <h1 id="counter"><?= $countData['countername']; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3 text-center">
                    <div class="hpanel hbgblue">
                        <div class="panel-body">
                            <div class="text-center">
                                <h3><i class="fa fa-hourglass-2"></i> คิวรอ</h3>
                                <h1 id="qcount"><?= $countData['count']; ?></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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