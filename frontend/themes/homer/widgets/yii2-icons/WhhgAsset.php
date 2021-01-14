<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class WhhgAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/whhg';

    public $css = [
        YII_ENV_DEV ? 'css/whhg.css' : 'css/whhg.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
}