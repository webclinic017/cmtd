<?php

namespace app\assets;

use yii\web\AssetBundle;

class TesteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
        'css/business-frontpage.css'
    ];
 
    
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}