<?php
use yii\bootstrap\Tabs;
use yii\helpers\Url;
$action = Yii::$app->controller->action->id;
?>
<?php
echo Tabs::widget([
    'items' => [
        [
            'label' => 'รายงานระยะเวลาที่ใช้บริการ',
            'active' => $action == 'index' ? true : false,
            'options' => ['id' => 'tab-1'],
            'url' => Url::to(['index']),
        ],
        [
            'label' => 'รายงานจำนวนผู้ใช้บริการแบ่งเป็นช่วงเวลา (รายวัน)',
            'active' => $action == 'duration' ? true : false,
            'options' => ['id' => 'tab-2'],
            'url' => Url::to(['duration']),
        ],
        [
            'label' => 'รายงานจำนวนผู้ใช้บริการแบ่งเป็นช่วงเวลา (ภาพรวม)',
            'active' => $action == 'duration-summary' ? true : false,
            'options' => ['id' => 'tab-3'],
            'url' => Url::to(['duration-summary']),
        ],
        [
            'label' => 'รายงานระยะเวลารอคอย',
            'active' => $action == 'timewaiting' ? true : false,
            'options' => ['id' => 'tab-4'],
            'url' => Url::to(['timewaiting']),
        ],
    ],
    'renderTabContent' => false,
    'encodeLabels' => false,
]);
?>
<?php
$this->registerJs(<<<JS
//hidden menu
$('body').addClass('hide-sidebar');
JS
);
?>