<?php
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@homer/assets');
?>
<?php $this->beginContent('@homer/views/layouts/base.php',['class' => \Yii::$app->keyStorage->get('frontend.body.class', \Yii::$app->name)]); ?>
<?= $this->render('header',['directoryAsset' => $directoryAsset]) ?>
<?= $this->render('side-menu',['directoryAsset' => $directoryAsset]) ?>
<div id="wrapper">
    <?= $this->render('content',['content' => $content,'directoryAsset' => $directoryAsset]) ?>
    <?= $this->render('footer',['directoryAsset' => $directoryAsset]) ?>
</div>
<?php $this->endContent(); ?>