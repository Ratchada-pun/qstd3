<?php

/**
 * Created by PhpStorm.
 * User: Tanakorn Phompak
 * Date: 23/9/2562
 * Time: 15:00
 */

use yii\bootstrap\Tabs;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
use yii\icons\Icon;
use homer\widgets\Table;
use frontend\modules\app\models\TbCounterservice;
use frontend\modules\app\models\TbServiceProfile;
use yii\helpers\ArrayHelper;
#assets
use homer\assets\SocketIOAsset;
use homer\assets\jPlayerAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use homer\assets\ICheckAsset;
use homer\assets\HomerAdminAsset;

SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);
ICheckAsset::register($this);

$this->title = 'เรียกคิวรับยา';

$this->registerCss(<<<CSS
/* .normalheader {
	display: none;
} */
html.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown), body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
    overflow-y: unset !important;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    border: 1px solid #74d348;
    border-bottom-color: transparent;
}
.modal-open {
    overflow: unset;
}
input[type=search]:focus {
    background-color: #434a54;
    color: white;
}
table.dataTable span.highlight {
    background-color: #f0ad4e;
    color: white;
}
.select2-dropdown {
    z-index: 9999;
}
@media (max-width: 1920px) {
    .radio-inline + .radio-inline, .checkbox-inline + .checkbox-inline {
        margin-left: 0px;
    }
}
div.dataTables_wrapper div.dataTables_filter{
    text-align: left;
}
div.dataTables_wrapper div.dataTables_length{
    float: right;
}
div.dt-buttons{
    float: right;
}
.btn-recall,
.btn-hold,
.btn-end,
.btn-calling,
.btn-transfer{
    border-radius: 25px;
}
.checkbox label:after,
.radio label:after {
    content: '';
    display: table;
    clear: both;
}

.checkbox .cr,
.radio .cr {
    position: relative;
    display: inline-block;
    border: 1px solid #a9a9a9;
    border-radius: .25em;
    width: 1.3em;
    height: 1.3em;
    float: left;
    margin-right: .5em;
}

.radio .cr {
    border-radius: 50%;
}

.checkbox .cr .cr-icon,
.radio .cr .cr-icon {
    position: absolute;
    font-size: .8em;
    line-height: 0;
    top: 50%;
    left: 20%;
}

.radio .cr .cr-icon {
    margin-left: 0.04em;
}

.checkbox label input[type="checkbox"],
.radio label input[type="radio"] {
    display: none;
}

.checkbox label input[type="checkbox"] + .cr > .cr-icon,
.radio label input[type="radio"] + .cr > .cr-icon {
    transform: scale(3) rotateZ(-20deg);
    opacity: 0;
    transition: all .3s ease-in;
}

.checkbox label input[type="checkbox"]:checked + .cr > .cr-icon,
.radio label input[type="radio"]:checked + .cr > .cr-icon {
    transform: scale(1) rotateZ(0deg);
    opacity: 1;
}

.checkbox label input[type="checkbox"]:disabled + .cr,
.radio label input[type="radio"]:disabled + .cr {
    opacity: .5;
}

footer.footer {
    display: none;
}
#tab-menu > li.active a,
#tab-menu > li.active a:hover {
    background-color: #1a7bb9 !important;
    color: #FFFFFF !important;
}
#back-to-top {
    display: none !important;
}
CSS
);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var modelForm = ' . Json::encode($modelForm) . '; ', View::POS_HEAD);
$this->registerJs('var modelProfile = ' . Json::encode($modelProfile) . '; ', View::POS_HEAD);
$this->registerJs('var select2Data = ' . Json::encode(ArrayHelper::map(TbCounterservice::find()->where([
        'counterservice_type' => $modelProfile['counterservice_typeid'],
        'counterservice_status' => 1
    ])->asArray()->orderBy(['service_order' => SORT_ASC])->all(), 'counterserviceid', 'counterservice_name')) . '; ', View::POS_HEAD);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12" style="">
        <div class="hpanel">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => '<i class="pe-7s-volume"></i> ' . $this->title,
                        'active' => true,
                        'options' => ['id' => 'tab-1'],
                        'linkOptions' => ['style' => 'font-size: 14px;'],
                    ],
                ],
                'options' => ['class' => 'nav nav-tabs'],
                'encodeLabels' => false,
                'renderTabContent' => false,
            ]);
            ?>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body" style="padding-buttom: 0px;">
                        <div class="row">
                            <div class="col-md-12 text-center text-tablet-mode" style="display: none;">
                                <p><span style="font-weight: bold;text-align: center;font-size: 18px;">ห้องตรวจ</span>
                                </p>
                            </div>
                        </div>
                        <!-- Begin From -->
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="hpanel panel-form">
                                <div class="panel-heading">
                                    <div class="panel-tools">
                                        <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                                    </div>
                                    <div class="checkbox" style="display: inline-block;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="0" name="tablet-mode" id="tablet-mode">
                                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                            <i class="pe-7s-phone"></i> Tablet Mode
                                        </label>
                                    </div>
                                    <div class="checkbox" style="display: inline-block;margin-bottom: 0px;">
                                        <label>
                                            <input type="checkbox" value="0" name="tablet-mode" id="fullscreen-toggler">
                                            <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                                            <i class="pe-7s-expand1"></i> Fullscreen
                                        </label>
                                    </div>
                                    <span class="panel-heading-text" style="font-size: 18px;">&nbsp;</span>
                                </div>
                                <div class="panel-body"
                                     style="border: 1.5px dashed lightgrey;padding-left: 10px;padding-bottom: 0px;padding-top: 0px;">
                                    <?php
                                    $form = ActiveForm::begin([
                                        'id' => 'calling-form',
                                        'type' => 'horizontal',
                                        'options' => ['autocomplete' => 'off'],
                                        'formConfig' => ['showLabels' => false],
                                    ]) ?>
                                    <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                        <div class="col-md-3 service_profile">
                                            <?=
                                            $form->field($modelForm, 'service_profile')->widget(Select2::classname(), [
                                                'data' => ArrayHelper::map(TbServiceProfile::find()->where(['service_profile_status' => 1])->asArray()->all(), 'service_profile_id', 'service_name'),
                                                'options' => ['placeholder' => 'เลือกโปรไฟล์...'],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                                'size' => Select2::LARGE,
                                                'pluginEvents' => [
                                                    "change" => "function() {
                                                    if($(this).val() != '' && $(this).val() != null){
                                                        location.replace(baseUrl + \"/app/calling/medicine-room?profileid=\" + $(this).val());
                                                    }else{
                                                        location.replace(baseUrl + \"/app/calling/medicine-room\");
                                                    }
                                                }",
                                                ]
                                            ]);
                                            ?>
                                        </div>

                                        <div class="col-md-3 last-queue">
                                            <ul class="list-group">
                                                <li class="list-group-item" style="font-size:18px;">
                                                    <span class="badge badge-primary" style="font-size:18px;"
                                                          id="last-queue">-</span>
                                                    คิวที่เรียกล่าสุด
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-3">
                                            <?= $form->field($modelForm, 'qnum')->textInput([
                                                'class' => 'input-lg',
                                                'placeholder' => 'คีย์หมายเลขคิวที่นี่เพื่อเรียก',
                                                'style' => 'background-color: #434a54;color: white;',
                                                'autofocus' => true
                                            ])->hint(''); ?>
                                        </div>
                                        <div class="col-md-3">
                                            <p>
                                                <?= Html::a('CALL NEXT', false, ['class' => 'btn btn-lg btn-block btn-primary activity-callnext']); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 5px;">
                                        <div class="col-md-12">
                                            <?= $modelForm->serviceList; ?>
                                        </div>
                                    </div>
                                    <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                        <div class="col-md-12">
                                            <?= $form->field($modelForm, 'counter_service')->checkboxList($modelForm->getDataCounterserviceEx($modelProfile), [
                                                'inline' => true,
                                                'class' => 'i-checks'
                                            ]) ?>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end() ?>
                                </div><!-- End panel body -->
                            </div><!-- End hpanel -->
                        </div>

                        <div class="form-group call-next-tablet-mode" style="margin-bottom: 5px;display: none">
                            <div class="col-md-9" style="padding-bottom: 5px;">
                                <ul class="list-group">
                                    <li class="list-group-item text-primary" style="font-size:18px;">
                                        <span class="badge badge-primary last-queue" style="font-size:18px;"
                                              id="last-queue">-</span>
                                        คิวที่เรียกล่าสุด
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <p>
                                    <?= Html::a('CALL NEXT', false, ['class' => 'btn btn-lg btn-block btn-primary activity-callnext']); ?>
                                </p>
                            </div>
                        </div>
                        <!-- End Form -->
                        <div class="col-xs-12 col-sm-12 col-md-6" style="">
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
                                        <div class="panel-body" style="padding-buttom: 0px;">
                                            <div class="row">
                                                <div class="col-md-12 text-center text-tablet-mode"
                                                     style="display: none">
                                                    <p><span class="label label-primary"
                                                             style="font-weight: bold;text-align: center;font-size: 1em;">คิวรอเรียก</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            echo Table::widget([
                                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-waiting'],
                                                //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                                'beforeHeader' => [
                                                    [
                                                        'columns' => [
                                                            ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ชื่อ-นามสกุล', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'เวลามาถึง', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ห้องตรวจ', 'options' => ['style' => 'text-align: center;']],
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
                                </div>
                            </div><!-- End hpanel -->
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6" style="">
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
                                                <div class="col-md-12 text-center text-tablet-mode"
                                                     style="display: none">
                                                    <p><span class="label label-primary"
                                                             style="font-weight: bold;text-align: center;font-size: 1em;">คิวกำลังเรียก</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            echo Table::widget([
                                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-calling'],
                                                //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                                'beforeHeader' => [
                                                    [
                                                        'columns' => [
                                                            ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
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
                                        </div><!-- End panel body -->
                                    </div>
                                    <div id="tab-hold" class="tab-pane">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-12 text-center text-tablet-mode"
                                                     style="display: none">
                                                    <p><span class="label label-primary"
                                                             style="font-weight: bold;text-align: center;font-size: 1em;">พักคิว</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                            echo Table::widget([
                                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-hold'],
                                                //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                                'beforeHeader' => [
                                                    [
                                                        'columns' => [
                                                            ['content' => '#', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'หมายเลขคิว', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'HN', 'options' => ['style' => 'text-align: center;']],
                                                            ['content' => 'ประเภท', 'options' => ['style' => 'text-align: center;']],
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
echo $this->render('_datatables', ['modelForm' => $modelForm, 'modelProfile' => $modelProfile, 'action' => Yii::$app->controller->action->id]);

$this->registerJs(<<<JS
$(function() {
    //hidden menu
    $('body').addClass('hide-sidebar');
    //$('input[type="search"]').removeClass('input-sm').addClass('input-lg');

    $('input[type="search"]').focus(function(){
        //animate
        $(this).animate({
            width: '250px',
        }, 400 )
    }); 

    $('input[type="search"]').blur(function(){
        $(this).animate({
            width: '160px'
        }, 500 );
    });

    $('#callingform-qnum').keyup(function(){
        this.value = this.value.toUpperCase();
    });

    // Toastr options
    toastr.options = {
        "debug": false,
        "newestOnTop": false,
        "positionClass": "toast-top-center",
        "closeButton": true,
        "toastClass": "animated fadeInDown",
    };

    // Initialize iCheck plugin
    var input = $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_square-green'
    });

    //Checkbox Event
    $(input).on('ifChecked', function(event){
        var key = $(this).val();
        Queue.setDataSession();
    });

    $(input).on('ifUnchecked', function(event){
        var key = $(this).val();
        Queue.setDataSession();
    });
});

Queue = {
    setDataSession: function(){
        var \$form = $('#calling-form');
        var data = \$form.serialize();
        $.ajax({
            method: "POST",
            url: "/app/calling/set-counter-session?page=medicine-room",
            data: data,
            dataType: "json",
            beforeSend: function(jqXHR, settings){
                swal({
                    title: 'Loading...',
                    text: '',
                    onOpen: () => {
                        swal.showLoading()
                    }
                }).then((result) => {
                });
            },
            success: function(res){
                swal.close();
                location.reload();
            },
            error:function(jqXHR, textStatus, errorThrown){
                Queue.ajaxAlertError(errorThrown);
            }
        });
    },
    handleEventClick: function(){
        var self = this;
        //เรียกคิวรอ
        $('#tb-waiting tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbwaiting.row( tr ).data();
            var objKeys = Object.keys(select2Data);
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                input: 'select',
                type: 'question',
                inputOptions: select2Data,
                inputPlaceholder: 'เลือกช่องจ่ายยา',
                inputValue: objKeys.length ?  objKeys[0] || '' : '',
                inputClass: 'form-control m-b',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                inputValidator: (value) => {
                    if (!value) {
                    return 'คุณไม่ได้เลือกช่องจ่ายยา!'
                    }
                },
                preConfirm: (value) => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/call-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                                value:value //select value
                            },
                            success: function(res){
                                if(res.status == 200){
                                    self.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    self.toastrSuccess('CALL ' + data.qnumber);
                                    socket.emit('call', res);//sending data
                                    resolve();
                                }else{
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                }
            });
            jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกช่องจ่ายยา...","language":"th",sorter: function(data) {
                return data.sort(function(a, b) {
                    return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                });
            }});
            $('select.swal2-select, span.select2').addClass('input-lg');
            $('#swal2-content').css('padding-bottom','15px');
        });
        
        $('#tb-waiting tbody').on( 'click', 'tr td a.btn-transfer', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbwaiting.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbwaiting.row( tr ).data();
            swal({
                title: 'ส่งกลับหัองตรวจ '+data.qnumber+' ?',
                html: '<p><i class="fa fa-user"></i> '+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ส่งกลับ',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/transfer-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){
                                    self.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                    socket.emit('transfer-examination-room', res);//sending data
                                    resolve();
                                }else{
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                }
            });
        });

        //เรียกคิวซ้ำ
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-recall', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/recall-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){
                                    //dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียก
                                    self.toastrSuccess('RECALL ' + data.qnumber);
                                    socket.emit('call', res);//sending data
                                    resolve();
                                }else{
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        //พักคิว
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-hold', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ยืนยันพักคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'พักคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/hold-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    self.toastrSuccess('HOLD ' + data.qnumber);
                                    socket.emit('hold', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        //เรียกคิว hold
        $('#tb-hold tbody').on( 'click', 'tr td a.btn-calling', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbhold.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbhold.row( tr ).data();
            swal({
                title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'เรียกคิว',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/callhold-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียก
                                    self.reloadTbHold();//โหลดข้อมูลพักคิว
                                    self.toastrSuccess('CALL ' + data.qnumber);
                                    socket.emit('call', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        //End คิว hold
        $('#tb-hold tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbhold.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbhold.row( tr ).data();
            swal({
                title: 'ยืนยัน End คิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/endhold-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    self.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                    self.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                    self.toastrSuccess('END ' + data.qnumber);
                                    socket.emit('finish', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });

        //End คิว
        $('#tb-calling tbody').on( 'click', 'tr td a.btn-end', function (event) {
            event.preventDefault();
            var tr = $(this).closest("tr");
            if(tr.hasClass("child") && typeof dt_tbcalling.row( tr ).data() === "undefined"){
                tr = $(this).closest("tr").prev();
            }
            var key = tr.data("key");
            var data = dt_tbcalling.row( tr ).data();
            swal({
                title: 'ยืนยัน End คิว '+data.qnumber+' ?',
                text: data.pt_name,
                html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
                type: 'question',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function() {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/app/calling/end-medicine-room",
                            dataType: "json",
                            data: {
                                data:data,//Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    self.reloadTbCalling();//โหลดข้อมูล
                                    self.toastrSuccess('END ' + data.qnumber);
                                    socket.emit('finish', res);//sending data
                                    resolve();
                                }else{//error
                                    self.ajaxAlertWarning();
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                self.ajaxAlertError(errorThrown);
                            }
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {//Confirm
                    swal.close();
                }
            });
        });
    },
    init: function(){
        var self = this;
        self.handleEventClick();
    },
    reloadTbWaiting: function(){
        dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอ
    },
    reloadTbCalling: function(){
        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียก
    },
    reloadTbHold: function(){
        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
    },
    toastrSuccess: function(msg){
        if(localStorage.getItem('disablealert-pagecalling') == 'on'){
            toastr.success(msg, 'Success!', {
                "timeOut": 3000,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "closeButton": true,
            });
        }
    },
    toastrWarning: function(title = 'Warning!',msg = ''){
        if(localStorage.getItem('disablealert-pagecalling') == 'on'){
            toastr.success(msg, title, {
                "timeOut": 5000,
                "positionClass": "toast-top-right",
                "progressBar": true,
                "closeButton": true,
            });
        }
    },
    ajaxAlertError: function(msg){
        swal({
            type: 'error',
            title: msg,
            showConfirmButton: false,
            timer: 1500
        });
    },
    ajaxAlertWarning: function(){
        swal({
            type: 'error',
            title: 'เกิดข้อผิดพลาด!!',
            showConfirmButton: false,
            timer: 1500
        });
    },
    checkCounter: function(){
        if(modelForm.service_profile == null){
            var title = 'กรุณาเลือกโปรไฟล์';
            swal({
                type: 'warning',
                title: title,
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }else{
            return true;
        }
    }
};

//socket event
$(function() {
    socket
    .on('finish', (res) => {
        var services = (modelProfile.service_id).split(',');
        if(jQuery.inArray((res.modelQ.serviceid).toString(), services) != -1) {//ถ้าคิวมีใน service profile
            Queue.reloadTbWaiting();//โหลดข้อมูลรอเรียก
            Queue.toastrWarning('ผู้ป่วยลงทะเบียนใหม่!',res.modelQ.pt_name);
        }
    })
    .on('finish', (res) => {
        var services = (modelProfile.service_id).split(',');
        if(jQuery.inArray((res.modelQ.serviceid).toString(), services) != -1) {//ถ้าคิวมีใน service profile
            Queue.reloadTbWaiting();//โหลดข้อมูลรอเรียก
        }
    })
    .on('call', (res) => {//เรียกคิวรอ
        if(res.eventOn === 'tb-waiting' && res.state === 'call'){//เรียกคิวจาก table คิวรอพบแพทย์
            Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
            var counters = modelForm.counter_service;
            if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
                Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            }
            swal.close();
        }else if(res.eventOn === 'tb-hold' && res.state === 'call-hold'){//เรียกคิวจาก table พักคิว
            var counters = modelForm.counter_service;
            if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
                Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
            }
        }
    })
    .on('hold', (res) => {//Hold คิวห้องตรวจ /kiosk/calling/examination-room
        var counters = modelForm.counter_service;
        if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
            Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
        }
    })
    .on('finish', (res) => {//จบ Process q /kiosk/calling/examination-room
        var counters = modelForm.counter_service;
        if(jQuery.inArray(res.counter.counterserviceid.toString(), counters) != -1){
            Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
        }
    }).on('display', (res) => {
        setTimeout(function(){
            dt_tbcalling.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                var data = this.data();
                if(data.qnumber == res.title){
                    $('#tb-calling').find('tr.success').removeClass("success");
                    $("#last-queue,.last-queue").html(data.qnumber);
                    dt_tbcalling.$("tr#"+res.artist.data.DT_RowId).addClass("success");
                    Queue.toastrWarning('', '<i class="pe-7s-speaker"></i> กำลังเรียกคิว #'+data.qnumber);
                }
            });
        }, 500);
    });
});

//CallNext
$('a.activity-callnext').on('click',function(e){
    e.preventDefault();
    var data = dt_tbwaiting.rows(0).data();
    var objKeys = Object.keys(select2Data);
    if(data.length > 0){
        swal({
            title: 'ยืนยันเรียกคิว '+data[0].qnumber+' ?',
            html: '<p><i class="fa fa-user"></i> '+data[0].pt_name+'</p>',
            input: 'select',
            type: 'question',
            inputOptions: select2Data,
            inputPlaceholder: 'เลือกช่องจ่ายยา',
            inputValue: objKeys.length ?  objKeys[0] || '' : '',
            inputClass: 'form-control m-b',
            showCancelButton: true,
            confirmButtonText: 'เรียกคิว',
            cancelButtonText: 'ยกเลิก',
            allowOutsideClick: false,
            inputValidator: (value) => {
                if (!value) {
                return 'คุณไม่ได้เลือกช่องจ่ายยา!'
                }
            },
            preConfirm: (value) => {
                return new Promise((resolve) => {
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/app/calling/call-medicine-room",
                        dataType: "json",
                        data: {
                            data:data[0],//Data in column Datatable
                            modelForm: modelForm, //Data Model CallingForm
                            modelProfile: modelProfile,
                            value:value //select value
                        },
                        success: function(res){
                            if(res.status == 200){
                                Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                Queue.toastrSuccess('CALL ' + data[0].qnumber);
                                socket.emit('call', res);//sending data
                                resolve();
                            }else{
                                Queue.ajaxAlertWarning();
                            }
                        },
                        error:function(jqXHR, textStatus, errorThrown){
                            Queue.ajaxAlertError(errorThrown);
                        }
                    });
                });
            }
        });
        jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกช่องจ่ายยา...","language":"th"});
        $('select.swal2-select, span.select2').addClass('input-lg');
        $('#swal2-content').css('padding-bottom','15px');
    }else{
        swal({
          type: 'warning',
          title: 'ไม่พบหมายเลขคิว',
          showConfirmButton: false,
          timer: 1500
        });
    }
});

var \$form = $('#calling-form');
\$form.on('beforeSubmit', function() {
    var dataObj = {};
    var qcall;

    \$form.serializeArray().map(function(field){
        dataObj[field.name] = field.value;
    });

    if(dataObj['CallingForm[qnum]'] != null && dataObj['CallingForm[qnum]'] != ''){
        //ข้อมูลกำลังเรียก
        dt_tbcalling.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.qnumber === dataObj['CallingForm[qnum]']){
                qcall = {data:data,tbkey:'tbcalling'};
            }
        });
        //ข้อมูลคิวรอพบแพทย์
        dt_tbwaiting.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.qnumber === dataObj['CallingForm[qnum]']){
                qcall = {data:data,tbkey:'tbwaiting'};
            }
        });
        //ข้อมูลพักคิว
        dt_tbhold.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
            var data = this.data();
            if(data.qnumber === dataObj['CallingForm[qnum]']){
                qcall = {data:data,tbkey:'tbhold'};
            }
        });

        if(qcall === undefined){
            toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-right"});
        }else{
            if(qcall.tbkey === 'tbcalling'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + "/app/calling/recall-medicine-room",
                                dataType: "json",
                                data: {
                                    data:qcall.data,//Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile
                                },
                                success: function(res){
                                    if(res.status == 200){
                                        Queue.toastrSuccess('RECALL ' + qcall.data.qnumber);
                                        socket.emit('call', res);//sending data
                                        resolve();
                                    }else{
                                        Queue.ajaxAlertWarning();
                                    }
                                },
                                error:function(jqXHR, textStatus, errorThrown){
                                    Queue.ajaxAlertError(errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) {//Confirm
                        swal.close();
                    }
                });
            }else if(qcall.tbkey === 'tbhold'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        return new Promise(function(resolve, reject) {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + "/app/calling/callhold-medicine-room",
                                dataType: "json",
                                data: {
                                    data: qcall.data,//Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile
                                },
                                success: function(res){
                                    if(res.status == 200){//success
                                        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                        Queue.reloadTbHold();//โหลดข้อมูลพักคิวใหม่
                                        Queue.toastrSuccess('CALL ' + qcall.data.qnumber);
                                        socket.emit('call', res);//sending data
                                        resolve();
                                    }else{//error
                                        Queue.ajaxAlertWarning();
                                    }
                                },
                                error:function(jqXHR, textStatus, errorThrown){
                                    Queue.ajaxAlertError(errorThrown);
                                }
                            });
                        });
                    },
                }).then((result) => {
                    if (result.value) {//Confirm
                        swal.close();
                    }
                });
            }else if(qcall.tbkey === 'tbwaiting'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    html: '<p><i class="fa fa-user"></i> '+qcall.data.pt_name+'</p>',
                    input: 'select',
                    type: 'question',
                    inputOptions: select2Data,
                    inputPlaceholder: 'เลือกช่องจ่ายยา',
                    inputValue: qcall.data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    showLoaderOnConfirm: true,
                    inputValidator: (value) => {
                        if (!value) {
                        return 'คุณไม่ได้เลือกช่องจ่ายยา!'
                        }
                    },
                    preConfirm: (value) => {
                        return new Promise((resolve) => {
                            $.ajax({
                                method: "POST",
                                url: baseUrl + "/app/calling/call-medicine-room",
                                dataType: "json",
                                data: {
                                    data: qcall.data,//Data in column Datatable
                                    modelForm: modelForm, //Data Model CallingForm
                                    modelProfile: modelProfile,
                                    value:value //select value
                                },
                                success: function(res){
                                    if(res.status == 200){
                                        Queue.reloadTbWaiting();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                        Queue.reloadTbCalling();//โหลดข้อมูลกำลังเรียกใหม่
                                        Queue.toastrSuccess('CALL ' + qcall.data.qnumber);
                                        socket.emit('call', res);//sending data
                                        resolve();
                                    }else{
                                        Queue.ajaxAlertWarning();
                                    }
                                },
                                error:function(jqXHR, textStatus, errorThrown){
                                    Queue.ajaxAlertError(errorThrown);
                                }
                            });
                        });
                    }
                });
                jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกช่องจ่ายยา...","language":"th"});
                $('select.swal2-select, span.select2').addClass('input-lg');
                $('#swal2-content').css('padding-bottom','15px');
            }
        }
    }else{
        toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-right"});
    }
    $('input#callingform-qnum').val(null);//clear data
    return false;
});

Queue.init();

$('#fullscreen-toggler')
    .on('click', function (e) {
        setFullScreen();
    });

function setFullScreen(){
    var element = document.documentElement;
    if (!$('body')
        .hasClass("full-screen")) {

        $('body')
            .addClass("full-screen");
        $('#fullscreen-toggler').addClass("active");
        localStorage.setItem('medical-fullscreen','true');
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }

    } else {

        $('body')
            .removeClass("full-screen");
        $('#fullscreen-toggler')
            .removeClass("active");
        localStorage.setItem('medical-fullscreen','false');
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }

    }
}

function setTabletmode(){
    var hpanel = $('div.panel-form');
    var icon = $('div.panel-form').find('i:first');
    var body = hpanel.find('div.panel-body');
    var footer = hpanel.find('div.panel-footer');

    if(localStorage.getItem('medicine-tablet-mode') == 'true'){

        body.slideToggle(300);
        footer.slideToggle(200);
        // Toggle icon from up to down
        icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
        hpanel.toggleClass('').toggleClass('panel-collapse');
        setTimeout(function () {
            hpanel.resize();
            hpanel.find('[id^=map-]').resize();
        }, 50);
        var profilename = $('#callingform-service_profile').select2('data')[0]['text'] || '';
        var counternames = [];
        $('#callingform-counter_service input[type="checkbox"]').each(function( index, value ) {
            var el = $(this);
            if (el.is(':checked')){
                counternames.push(el.closest('label').text());
            } 
        });
        $('div.panel-form .panel-heading-text').html(' | ' + profilename + ': ' + counternames.join(" , "));
        $('#tablet-mode').prop("checked", true);
        $('#tab-menu-default,#tab-menu-default1,.small-header').css("display","none");
        $('.footer-tabs,.call-next-tablet-mode,.text-tablet-mode').css("display","");
        $('#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body').css("border-top","1px solid #e4e5e7");
    }else{
        if(hpanel.hasClass('panel-collapse')){
            body.slideToggle(300);
            footer.slideToggle(200);
            // Toggle icon from up to down
            icon.toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
            hpanel.toggleClass('').toggleClass('panel-collapse');
            setTimeout(function () {
                hpanel.resize();
                hpanel.find('[id^=map-]').resize();
            }, 50);
        }
        $('div.panel-form .panel-heading-text').html('&nbsp;');
        $('.footer-tabs,.call-next-tablet-mode,.text-tablet-mode').css("display","none");
        $('#tab-menu-default,#tab-menu-default1,.small-header').css("display","");
        $('#tab-watting .panel-body,#tab-calling .panel-body,#tab-hold .panel-body').css("border-top","0");
    }
}

$(document).ready(function() {
    $('#tablet-mode').on('click',function(){
        if($(this).is(':checked')){
            localStorage.setItem('medicine-tablet-mode','true');
        }else{
            localStorage.setItem('medicine-tablet-mode','false');
        }
        setTabletmode();
    });
} );

setTabletmode();
JS
);
?>
