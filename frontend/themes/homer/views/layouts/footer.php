<?php
use yii\helpers\Html;
?>
<!-- Footer-->
<footer class="footer">
    <span class="pull-right">
        <?= Html::encode(\Yii::$app->keyStorage->get('hospital-email', Yii::powered())) ?>
    </span>
    &copy; <?= Html::encode(\Yii::$app->keyStorage->get('copy-right', Yii::$app->name)) ?> <?= date('Y') ?>
</footer>