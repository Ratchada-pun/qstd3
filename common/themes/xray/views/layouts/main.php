<?php

Yii::$app->assetManager->bundles[] = [
  'yii\bootstrap\BootstrapAsset' => false,
  'yii\bootstrap\BootstrapPluginAsset' => false,
];
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@xray/assets/dist');
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
  <!-- Sidebar  -->
  <?= $this->render('_sidebar.php', ['directoryAsset' => $directoryAsset]) ?>
  <!-- Sidebar  -->
  <!-- Page Content  -->
  <div id="content-page" class="content-page">
    <?= $this->render('_header.php', ['directoryAsset' => $directoryAsset]) ?>

    <div class="container-fluid">
      <?= $content ?>
    </div>

    <?= $this->render('_footer.php', ['directoryAsset' => $directoryAsset]) ?>
  </div>
  <!-- Page Content  -->
</div>
<!-- Wrapper END -->
<?php $this->endContent(); ?>