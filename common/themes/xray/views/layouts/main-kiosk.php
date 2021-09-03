<?php
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@xray/assets/dist');

$csscdn = [
  'https://fonts.googleapis.com/css?family=Poppins:200,200i,300,400,500,600,700,800,900|Roboto:300,400,500,600,700|Sarabun:300,400,500,600,700|Sriracha:300,400,500,600,700|Prompt:300,400,500,600,700',
  'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
  'https://unpkg.com/nprogress@0.2.0/nprogress.css',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css'
];

$jscdn = [
  'https://unpkg.com/nprogress@0.2.0/nprogress.js',
  'https://unpkg.com/axios/dist/axios.min.js',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11',
  'https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js',
  'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/th.min.js',
  'https://cdn.socket.io/4.1.2/socket.io.min.js'
];

foreach ($csscdn as $key => $url) {
  $this->registerCssFile($url, [
    'depends' => [\yii\bootstrap4\BootstrapAsset::class],
  ]);
}

foreach ($jscdn as $key => $url) {
  $this->registerJsFile(
    $url,
    ['depends' => [\yii\web\JqueryAsset::class]]
  );
}
$this->registerJsFile(
  YII_ENV_DEV ? 'https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js' : 'https://cdn.jsdelivr.net/npm/vue@2.6.14',
  ['depends' => [\yii\web\JqueryAsset::class]]
);
?>
<?php $this->beginContent('@xray/views/layouts/_base.php'); ?>

<!-- loader Start -->
<div id="loading">
  <div id="loading-center">

  </div>
</div>
<!-- loader END -->

<!-- Wrapper Start -->
<div class="wrapper">
  <!-- Page Content  -->
  <div id="content-page" class="content-page" style="margin-left: 80px;margin-right: 80px;border-radius: 25px;">
    <?= $this->render('_header-kiosk.php', ['directoryAsset' => $directoryAsset]) ?>

    <div class="container-fluid">
      <?= $content ?>
    </div>

    <?= $this->render('_footer.php', ['directoryAsset' => $directoryAsset]) ?>
  </div>
  <!-- Page Content  -->
</div>
<!-- Wrapper END -->
<?php $this->endContent(); ?>