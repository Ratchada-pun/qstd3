<?php 
namespace homer\assets;

use yii\web\AssetBundle;

class CrudAsset extends AssetBundle
{
    public $sourcePath = '@homer/assets/ajaxcrud';

    public $css = [
        'css/ajaxcrud.css'
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
    
    public function init() {
       $this->js = YII_DEBUG ? [
           'js/ModalRemote.js',
           'js/ajaxcrud.js',
       ]:[
           'js/ModalRemote.min.js',
           'js/ajaxcrud.min.js',
       ];
       parent::init();
    }

}