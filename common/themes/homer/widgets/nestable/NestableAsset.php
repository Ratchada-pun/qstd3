<?php
namespace homer\widgets\nestable;

use yii\web\AssetBundle;

class NestableAsset extends AssetBundle 
{
	public $sourcePath = '@homer/assets/vendor/nestable';

    public $js = [
		'jquery.nestable.js'
	];
	
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}