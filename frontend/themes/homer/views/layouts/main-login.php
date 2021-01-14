<?php
use yii\helpers\Html;
use yii\helpers\Url;

$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@homer/assets');
?>
<?php $this->beginContent('@homer/views/layouts/base.php',['class' => 'blank']); ?>

<div class="login-container" style="padding-top: 0;">
    <?= $content; ?>
</div>
<?php $this->endContent(); ?>