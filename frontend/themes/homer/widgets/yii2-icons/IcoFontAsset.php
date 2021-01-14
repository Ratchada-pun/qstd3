<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class IcoFontAsset extends BaseAssetBundle
{
    public $sourcePath = __DIR__ . '/lib/icofont';

    public $css = [
        YII_ENV_DEV ? 'css/icofont.css' : 'css/icofont.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}