<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class JuiAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/jquery-ui';
    /**
     * @inheritdoc
     */
    public $css = [
        YII_ENV_DEV ? 'themes/smoothness/jquery-ui.css' : 'themes/smoothness/jquery-ui.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}