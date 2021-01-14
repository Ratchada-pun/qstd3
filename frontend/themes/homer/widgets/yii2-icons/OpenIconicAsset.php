<?php
namespace kartik\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class OpenIconicAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/openiconic';

    public $css = [
        YII_ENV_DEV ? 'css/open-iconic-bootstrap.css' : 'css/open-iconic-bootstrap.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}