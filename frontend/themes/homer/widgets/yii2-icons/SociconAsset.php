<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class SociconAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/socicon';

    public $css = [
        YII_ENV_DEV ? 'css/socicon.css' : 'css/socicon.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

}