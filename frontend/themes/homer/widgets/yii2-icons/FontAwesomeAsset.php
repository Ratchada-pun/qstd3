<?php
namespace yii\icons;

use yii\web\AssetBundle as BaseAssetBundle;

class FontAwesomeAsset extends BaseAssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/lib/font-awesome';

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => ['fonts/*', 'css/*']
    ];

    public $css = [
        YII_ENV_DEV ? 'css/font-awesome.css' : 'css/font-awesome.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
