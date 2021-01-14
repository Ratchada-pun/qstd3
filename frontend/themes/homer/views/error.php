<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
$textColor = $exception->statusCode === 404 ? "text-warning" : "text-danger";
$btnClass = $exception->statusCode === 404 ? "btn-warning" : "btn-danger";
?>
<div class="color-line"></div>
<div class="back-link">
    <?= Html::a('Back to Dashboard',Url::home(true),['class' => 'btn btn-primary']); ?>
</div>
<div class="error-container">
    <i class="pe-7s-way <?= $textColor; ?> big-icon"></i>
    <h1 class="<?= $textColor ?>"><?= $exception->statusCode ?></h1>
    <h4><code><?= Html::encode($this->title) ?></code></h4>
    <p>
        <code>
            <?= nl2br(Html::encode($message)) ?>
        </code>
    </p>
    
    <?= Html::a('Go back to dashboard',Url::home(true),['class' => 'btn btn-xs '.$btnClass.'']); ?>
</div>