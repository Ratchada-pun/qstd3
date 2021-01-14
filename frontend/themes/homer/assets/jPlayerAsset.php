<?php

namespace homer\assets;

use yii\web\AssetBundle;

class jPlayerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
        'vendor/jPlayer/dist/jplayer/jquery.jplayer.min.js',
        'vendor/jPlayer/dist/add-on/jplayer.playlist.min.js',
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