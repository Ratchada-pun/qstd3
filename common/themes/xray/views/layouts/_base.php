<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset as FrontendAsset;
use backend\assets\AppAsset as BackendAsset;
use xray\assets\XrayAsset;
use yii\bootstrap4\Html;

if (Yii::$app->id === 'app-frontend') {
  FrontendAsset::register($this);
}
if (Yii::$app->id === 'app-backend') {
  BackendAsset::register($this);
}
XrayAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php $this->registerCsrfMetaTags() ?>
  <title><?= Html::encode($this->title) ?></title>
  <?php $this->head() ?>
</head>

<body class="sidebar-main-menu">
  <?php $this->beginBody() ?>

  <?= $content ?>

  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
