<?php

use yii\helpers\Html;
use yii\bootstrap\Tabs;
use homer\assets\SweetAlert2Asset;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;
use homer\assets\HomerAdminAsset;
use yii\helpers\Url;

// SweetAlert2Asset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);
HomerAdminAsset::register($this);

$this->title = 'ออกบัตรคิว';

$this->registerCss(
    <<<CSS
body {
    color: #333333;
    background-color: #0baabd;
}
body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
  overflow-y: unset !important;
}

.swal2-shown {
  overflow-x: auto !important;
}

.swal2-container.swal2-center.swal2-backdrop-show {
  overflow-y: auto !important;
}
.swal2-popup {
  font-size: 1.6rem !important;
}
.swal2-html-container {
    font-size: 5rem!important;
}
#logo.light-version {
    background-color: #ffffff;
    border-bottom: 1px solid #ffffff;
    text-align: center;
}
#logo {
    float: left;
    width: 180px;
    background-color: #34495e;
    padding: 0;
    height: 100px;
    text-align: center;
}
.btn-service {
    height: 90px;
    border-radius: 1.75rem;
    border: 2px solid #ffa800;
    font-size: 3rem;
    font-weight: 600;
}
CSS
);
?>
<div class="container">
    <div class="normalheader ">
        <div class="hpanel">
            <div class="panel-body" style="border-radius: 20px;">
                <div class="row">
                    <div class="col-md-2">
                        <div id="logo" class="light-version">
                            <?= Html::img(\Yii::$app->keyStorage->get('logo-url', '/img/logo/logoKM4.png'), ['style' => 'width: 100px;height: 100px;']) ?>
                        </div>
                    </div>
                    <div class="col-md-6" style="text-align: left; padding-top: 15px;">
                        <h1 class="font-light m-b-xs" style="font-size:45px;">
                            <span class="text-primary"><?= Yii::$app->keyStorage->get('kiosk-caption') ?></span>
                        </h1>
                    </div>
                    <div class="col-md-4" style="padding-top: 50px;">
                        <h3 class="font-light m-b-xs" style="font-size: 22px;">
                            <span class="text-primary"><?= Yii::$app->formatter->asDate('now', 'php:l ที่ d F ') . (Yii::$app->formatter->asDate('now', 'php:Y')  + 543) ?></span>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="hpanel">
            <div class="row">
                <div class="col-md-12" style="text-align: center; padding-top: 15px;">
                    <h1 class="font-light m-b-xs" style="font-size:45px;">
                        <span class="text-white"><?= Yii::$app->keyStorage->get('kiosk-sub-title') ?></span>
                    </h1>
                    <br>
                    <h2>
                        <span class="text-white">ยินดีให้บริการ </span>
                    </h2>
                    <div>
                        <?= Html::img(\Yii::$app->keyStorage->get('kiosk-hello-url', '/img/logo/logoKM4.png'),) ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align: left; padding-top: 15px;">
                <h4 class="font-light m-b-xs" style="font-size:45px;">
                    <span class="text-white">เลือกบริการ</span>
                </h4>
            </div>
        </div>

        <div class="row" style="padding-left: 100px;padding-right: 100px;">
            <?php
            foreach ($services as $key => $service) : ?>
                <div class="col-md-12" style="padding-top: 30px;">
                    <button type="button" class="btn btn-default btn-lg btn-block btn-service" data-url="<?= Url::to(['/api/kiosk/create-queue', 'serviceid' => $service['serviceid'], 'servicegroupid' => $service['service_groupid']]) ?>">
                        <?= $service['btn_kiosk_name'] ?>
                    </button>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>

<br>
<?php
echo $this->render('modal');

$this->registerJsFile('//cdn.jsdelivr.net/npm/sweetalert2@11',  ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs(
    <<<JS
socket
.on('register', (res) => {
    // dt_tbqdata.ajax.reload();
});

$('button.btn-service').on('click', function(e){
    e.preventDefault();
    var data = yii.getQueryParams($(this).data('url'))
    $.ajax({
        method: "POST",
        url: "http://andamandev.com/node/api/kiosk/create-queue",
        data: data,
        dataType: "json",
        success: function(res){
            window.open('/app/kiosk/print-ticket?id=' + res.modelQueue.q_ids,"myPrint","width=800, height=600");
        },
        error: function( jqXHR, textStatus, errorThrown) {
            if(jqXHR.responseJSON) {
                Swal.fire({
                    icon: 'warning',
                    title: 'ขออภัย',
                    text: jqXHR.responseJSON.message,
                    timer: 3000,
                    width: '64rem',
                    showConfirmButton: false
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: errorThrown,
                    timer: 3000,
                    width: '64rem',
                    showConfirmButton: false
                })
            }
        }
    });
});
JS
);
?>