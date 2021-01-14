<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class ElusiveAsset extends BaseAssetBundle
{
	public $sourcePath = __DIR__ . '/lib/elusive';

    public $css = [
        YII_ENV_DEV ? 'css/elusive-icons.css' : 'css/elusive-icons.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}