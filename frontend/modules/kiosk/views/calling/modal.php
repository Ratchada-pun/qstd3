<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use homer\assets\CrudAsset;

CrudAsset::register($this);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    "size" => "modal-lg",
    "options" => ["tabindex" => false],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
])?>
<?php Modal::end(); ?>