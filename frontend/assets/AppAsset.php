<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'
    ];
    public $js = [
        'js/clock.js',
        'js/config.js',
        'vendor/bootstrap-toggle/js/bootstrap-toggle.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
