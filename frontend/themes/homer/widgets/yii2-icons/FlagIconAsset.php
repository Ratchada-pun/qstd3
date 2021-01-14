<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class FlagIconAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ .'/lib/flag-icon-css';

    public $css = [
        YII_ENV_DEV ? 'css/flag-icon.css' : 'css/flag-icon.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}