<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use homer\assets\CrudAsset;

CrudAsset::register($this);
$this->registerCss(<<<CSS
.modal-title{
	font-size: 20px !important;
}
.modal-header{
	padding: 10px 10px !important;
}
CSS
);
?>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",
    "size" => "modal-lg",
    "options" => ["tabindex" => false],
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
])?>
<?php Modal::end(); ?>