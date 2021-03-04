<?php
#assets
use homer\assets\SocketIOAsset;
use homer\assets\jPlayerAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use yii\jui\JuiAsset;
use homer\assets\HomerAdminAsset;
use yii\bootstrap\BootstrapAsset;

use frontend\modules\app\models\TbCounterservice;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Url;

$bundle = BootstrapAsset::register($this);
$bundle->depends = ['yii\jui\JuiAsset'];
SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);
JuiAsset::register($this);

$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var modelForm = ' . Json::encode($modelForm) . '; ', View::POS_HEAD);
$this->registerJs('var modelProfile = ' . Json::encode($modelProfile) . '; ', View::POS_HEAD);
$this->registerJs('var select2Data = ' . Json::encode(ArrayHelper::map(TbCounterservice::find()->where([
        'counterserviceid' => !empty($modelProfile['counter_service_ids']) ? explode(",", $modelProfile['counter_service_ids']) : [],
        'counterservice_status' => 1
    ])->orderBy(['service_order' => SORT_ASC])->all(), 'counterserviceid', 'counterservice_name')) . '; ', View::POS_HEAD);
$this->registerJsFile(
    '@web/js/jquery.ui.touch-punch.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>