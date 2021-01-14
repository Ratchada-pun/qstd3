<?php
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
?>
<div class="panel-body">
<?php echo \mihaildev\elfinder\ElFinder::widget([
    'controller'       => 'file-manager-elfinder',
    'frameOptions' => ['style'=>'min-height: 500px; width: 100%; border: 0'],
    'language'         => 'en',
]);
?>
</div>