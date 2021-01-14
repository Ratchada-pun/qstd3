<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class TypiconsAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/typicons';

    public $css = [
        YII_ENV_DEV ? 'css/typicons.css' : 'css/typicons.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}