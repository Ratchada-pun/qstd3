<?php
namespace homer\assets;

use yii\web\AssetBundle;

class SweetAlert2Asset extends AssetBundle
{
    public $sourcePath = '@homer/assets/sweetalert2';
    
    public $css = [
        'dist/sweetalert2.css'
    ];

    public $js = [
        'dist/core.js',
        'dist/sweetalert2.all.min.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}