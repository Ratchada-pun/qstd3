<?php

namespace homer\assets;

use yii\web\AssetBundle;

class ICheckAsset extends AssetBundle
{
    public $sourcePath = '@homer/assets/vendor/iCheck';

    public $css = [
        'skins/all.css',
    ];
    public $js = [
        'icheck.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
    public function init()
    {
        parent::init();
    }
}