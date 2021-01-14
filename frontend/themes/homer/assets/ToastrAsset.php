<?php
namespace homer\assets;

use yii\web\AssetBundle;

class ToastrAsset extends AssetBundle 
{
    public $sourcePath = '@homer/assets/vendor/toastr';

    public $css = [
        'build/toastr.min.css', 
    ];

    public $js = [
        'build/toastr.min.js', 
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 