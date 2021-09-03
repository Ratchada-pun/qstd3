<?php

namespace xray\assets;

use yii\web\AssetBundle;

class XrayAsset extends AssetBundle
{
  public $sourcePath = '@xray/assets/dist';
  
  public $css = [
    YII_ENV_DEV ? 'css/typography.css' : 'css/typography.min.css',
    YII_ENV_DEV ? 'css/style.css' : 'css/style.min.css',
    YII_ENV_DEV ? 'css/responsive.css' : 'css/responsive.min.css'
  ];

  public $js = [
    YII_ENV_DEV ? 'js/smooth-scrollbar.js' : 'smooth-scrollbar.min.js',
    YII_ENV_DEV ? 'js/custom.js' : 'js/custom.min.js'
  ];

  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap4\BootstrapAsset',
    'yii\bootstrap4\BootstrapPluginAsset',
  ];
}
