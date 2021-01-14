<?php
namespace kartik\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class OcticonsAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/octicons';

    public $css = [
        YII_ENV_DEV ? 'css/octicons.css' : 'css/octicons.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}