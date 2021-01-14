<?php
namespace homer\assets;

use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle 
{
    public $sourcePath = '@homer/assets/vendor/font-awesome';

    public $css = [
        'css/font-awesome.min.css', 
    ];

    public $publishOptions = [
        'only' => [
            'fonts/*',
            'css/*',
        ]
    ];
}  