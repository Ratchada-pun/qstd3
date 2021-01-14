<?php
namespace homer\assets;

use yii\base\Exception;
use yii\web\AssetBundle as BaseAdminLteAsset;

class HomerAdminAsset extends BaseAdminLteAsset
{
    public $sourcePath = '@homer/assets';

    public $css = [
        'vendor/metisMenu/dist/metisMenu.css',
        'vendor/animate.css/animate.css',
        'fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css',
        'fonts/pe-icon-7-stroke/css/helper.css',
        'styles/static_custom.css',
        'styles/style.css'
    ];

    public $js = [
        'vendor/slimScroll/jquery.slimscroll.min.js',
        'vendor/metisMenu/dist/metisMenu.min.js',
        'vendor/iCheck/icheck.min.js',
        'vendor/sparkline/index.js',
        'scripts/homer.js'
    ];

    public $depends = [
        'homer\assets\FontAwesomeAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];

    public function init()
    {
        parent::init();
    }
}