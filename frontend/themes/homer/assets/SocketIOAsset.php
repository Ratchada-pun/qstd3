<?php

namespace homer\assets;

use yii\web\AssetBundle;

class SocketIOAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'vendor/socket.io/dist/socket.io.js',
        YII_ENV_DEV ? 'vendor/socket.io/dist/io.js' : 'vendor/socket.io/dist/io.min.js',
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