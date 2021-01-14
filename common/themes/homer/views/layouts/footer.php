<?php
use yii\helpers\Html;
?>
<!-- Footer-->
<footer class="footer">
    <span class="pull-right">
        <?= Yii::powered() ?>
    </span>
    &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
</footer>