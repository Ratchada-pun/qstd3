<?php

use yii\bootstrap\Tabs;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\icons\Icon;
use homer\widgets\Table;

$this->title = 'เรียกคิวจุดซักประวัติ';

$this->registerCss($this->render('style.css'));
$this->render('assets', ['modelProfile' => $modelProfile, 'modelForm' => $modelForm]);
?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12" style="">
        <div class="hpanel">
            <?php
            /* echo Tabs::widget([
                    'items' => [
                        [
                            'label' => '<i class="pe-7s-volume"></i> เรียกคิว',
                            'active' => true,
                            'options' => ['id' => 'tab-1'],
                            'linkOptions' => ['style' => 'font-size: 14px;'],
                        ],
                    ],
                    'options' => ['class' => 'nav nav-tabs'],
                    'encodeLabels' => false,
                    'renderTabContent' => false,
                ]); */
            ?>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body" style="padding-buttom: 0px;">
                        <div class="row">
                            <div class="col-md-12 text-center text-tablet-mode" style="display: none;">
                                <p><span style="font-weight: bold;text-align: center;font-size: 18px;">จุดซักประวัติ</span></p>
                            </div>
                        </div>
                        <?php /*
                        <div style="text-align:center; margin-bottom: 5px;">
                            <span class="badge badge-primary"><?= Icon::show('pe-7s-speaker',[],Icon::I).$this->title ?></span>
                        </div>
                        */ ?>
                        <!-- Begin From -->
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'calling-form',
                            'type' => 'horizontal',
                            'options' => ['autocomplete' => 'off'],
                            'formConfig' => ['showLabels' => false],
                        ]);
                        echo $this->render('_form_index', ['modelForm' => $modelForm, 'form' => $form]);
                        ActiveForm::end();
                        ?>

                        <!-- End Form -->

                        <div class="col-xs-12 col-sm-12 col-md-6" style="padding-left: 0px;">
                            <!-- Begin Panel -->
                            <div class="hpanel">
                                <?php
                                echo Tabs::widget([
                                    'items' => [
                                        [
                                            'label' => 'คิวรอเรียก ' . Html::tag('span', '0', ['id' => 'count-waiting', 'class' => 'badge count-waiting']),
                                            'active' => true,
                                            'options' => ['id' => 'tab-watting'],
                                            'linkOptions' => ['style' => 'font-size: 14px;'],
                                        ],
                                    ],
                                    'options' => ['class' => 'nav nav-tabs', 'id' => 'tab-menu-default1'],
                                    'encodeLabels' => false,
                                    'renderTabContent' => false,
                                ]);
                                ?>
                                <div class="tab-content">
                                    <div id="tab-watting" class="tab-pane active">
                                        <div class="panel-body" style="padding-buttom: 0px;border-top: 0;">
                                            <div class="row">
                                                <div class="col-md-12 text-center text-tablet-mode" style="display: none;">
                                                    <p>
                                                        <span class="label label-primary" style="font-weight: bold;text-align: center;font-size: 1em;">คิวรอเรียก</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            echo Table::widget([
                                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed table-striped', 'width' => '100%', 'id' => 'tb-waiting'],
                                                //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                                'beforeHeader' => [
                                                    [
                                                        'columns' => [
                                                            ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'คิว', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ผล Lab', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;width: 30px;']],
                                                        ],
                                                        'options' => ['style' => 'background-color:cornsilk;'],
                                                    ],
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- End hpanel -->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6" style="padding-right: 0px;">
                            <!-- Begin Panel -->
                            <div class="hpanel">
                                <?php
                                echo Tabs::widget([
                                    'items' => [
                                        [
                                            'label' => 'กำลังเรียก ' . Html::tag('span', '0', ['id' => 'count-calling', 'class' => 'badge count-calling']),
                                            'active' => true,
                                            'options' => ['id' => 'tab-calling'],
                                            'linkOptions' => ['style' => 'font-size: 14px;', 'class' => 'tabx'],
                                            'headerOptions' => ['class' => 'tab-calling']
                                        ],
                                        [
                                            'label' => 'พักคิว ' . Html::tag('span', '0', ['id' => 'count-hold', 'class' => 'badge count-hold']),
                                            'options' => ['id' => 'tab-hold'],
                                            'linkOptions' => ['style' => 'font-size: 14px;'],
                                            'headerOptions' => ['class' => 'tab-hold']
                                        ],
                                    ],
                                    'options' => ['class' => 'nav nav-tabs', 'id' => 'tab-menu-default'],
                                    'encodeLabels' => false,
                                    'renderTabContent' => false,
                                ]);
                                ?>
                                <div class="tab-content">
                                    <div id="tab-calling" class="tab-pane active">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center text-tablet-mode" style="display: none;">
                                                    <p>
                                                        <span class="label label-primary" style="font-weight: bold;text-align: center;font-size: 1em;">กำลังเรียก</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            echo Table::widget([
                                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed table-striped', 'width' => '100%', 'id' => 'tb-calling'],
                                                //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                                'beforeHeader' => [
                                                    [
                                                        'columns' => [
                                                            ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'คิว', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ห้อง/ช่อง/โต๊ะ', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ผล Lab', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                                                        ],
                                                        'options' => ['style' => 'background-color:cornsilk;'],
                                                    ]
                                                ],
                                            ]);
                                            ?>
                                        </div><!-- End panel body -->
                                    </div>
                                    <div id="tab-hold" class="tab-pane">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center text-tablet-mode" style="display: none;">
                                                    <p>
                                                        <span class="label label-primary" style="font-weight: bold;text-align: center;font-size: 1em;">พักคิว</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            echo Table::widget([
                                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed table-striped', 'width' => '100%', 'id' => 'tb-hold'],
                                                //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                                'beforeHeader' => [
                                                    [
                                                        'columns' => [
                                                            ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'คิว', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ห้องตรวจ/ช่องบริการ', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'สถานะ', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'prefix', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ผล Lab', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;']],
                                                        ],
                                                        'options' => ['style' => 'background-color:cornsilk;'],
                                                    ]
                                                ],
                                            ]);
                                            ?>
                                        </div>
                                    </div>
                                </div><!-- End panel body -->
                            </div><!-- End hpanel -->
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12" style="">
        <div class="footer footer-tabs" style="position: fixed;padding: 20px 18px;z-index: 3;">
            <div class="hpanel">
                <?php
                $icon = '<p style="margin: 0"><i class="fa fa-list" style="font-size: 1.5em;"></i> </p>';
                echo Tabs::widget([
                    'items' => [
                        [
                            'label' => $icon . ' คิวรอเรียก ' . Html::tag('span', '0', ['id' => 'count-waiting', 'class' => 'badge badge-info count-waiting']), 'options' => ['id' => 'tab-watting'],
                            'linkOptions' => ['style' => 'font-size: 14px;'],
                            'headerOptions' => ['style' => 'width: 33.33%;bottom: 20px;', 'class' => 'tab-watting text-center'],
                        ],
                        [
                            'label' => $icon . ' กำลังเรียก ' . Html::tag('span', '0', ['id' => 'count-calling', 'class' => 'badge badge-info count-calling']),
                            'active' => true,
                            'options' => ['id' => 'tab-calling'],
                            'linkOptions' => ['style' => 'font-size: 14px;', 'class' => 'tabx'],
                            'headerOptions' => ['style' => 'width: 33.33%;bottom: 20px;', 'class' => 'tab-calling text-center'],
                        ],
                        [
                            'label' => $icon . ' พักคิว ' . Html::tag('span', '0', ['id' => 'count-hold', 'class' => 'badge badge-info count-hold']),
                            'options' => ['id' => 'tab-hold'],
                            'linkOptions' => ['style' => 'font-size: 14px;'],
                            'headerOptions' => ['style' => 'width: 33.33%;bottom: 20px;', 'class' => 'tab-hold text-center']
                        ],
                    ],
                    'options' => ['class' => 'nav nav-tabs', 'id' => 'tab-menu'],
                    'encodeLabels' => false,
                    'renderTabContent' => false,
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<!-- jPlayer -->
<div id="jplayer_notify"></div>

<?php
echo $this->render('modal');
echo $this->render('_datatables', ['modelForm' => $modelForm, 'modelProfile' => $modelProfile, 'action' => Yii::$app->controller->action->id]);
$this->registerJs($this->render('script.js'));
?>