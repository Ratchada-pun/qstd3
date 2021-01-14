<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class UniAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/uni';

    public $css = [
        YII_ENV_DEV ? 'css/kv-unicode-icons.css' : 'css/kv-unicode-icons.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
}