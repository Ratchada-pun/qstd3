<?php
use yii\helpers\Html;
use yii\helpers\Url;

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@homer/assets');
?>
<?php $this->beginContent('@homer/views/layouts/base.php',['class' => 'blank hide-sidebar']); ?>
<?= $this->render('header',['directoryAsset' => $directoryAsset]) ?>
<div id="wrapper">
    <?= $this->render('content',['content' => $content,'directoryAsset' => $directoryAsset]) ?>
    <?= $this->render('footer',['directoryAsset' => $directoryAsset]) ?>
</div>
<?php $this->endContent(); ?>