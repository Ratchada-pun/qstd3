<?php
use yii\bootstrap\Tabs;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\ArrayHelper;
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use frontend\modules\kiosk\models\TbSection;
use frontend\modules\kiosk\models\TbCounterservice;

#assets
use homer\assets\SocketIOAsset;
use homer\assets\jPlayerAsset;
use homer\assets\CrudAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;

SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);

$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
$this->registerJs('var model = '.Json::encode($model).'; ',View::POS_HEAD);

$this->registerCss(<<<CSS
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
table.dataTable tbody tr td {
    font-size: 14px;
}
table.dataTable thead tr th {
    font-size: 14px;
}
table.dataTable span.highlight {
    background-color: #f0ad4e;
    color: white;
}
CSS
);

$this->title  = 'เรียกคิวห้องเจาะเลือด';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12" style="">
        <div class="hpanel">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => '<i class="pe-7s-volume"></i> '.$this->title,
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
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'calling-form',
                            'type' => 'horizontal',
                            'options' => ['autocomplete' => 'off'],
                            'formConfig' => ['showLabels' => false],
                        ]) ?>
                        <div class="hpanel">
                            <div class="panel-body" style="border: 1px dashed #dee5e7;padding-left: 10px;padding-bottom: 0px;padding-top: 0px;">
                                <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                    <div class="col-md-4">
                                        <?=
                                        $form->field($model, 'section')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(TbSection::find()->where(['sec_id' => 2])->asArray()->all(),'sec_id','sec_name'),
                                            'options' => ['placeholder' => 'เลือกแผนก...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'size' => Select2::LARGE,
                                            'pluginEvents' => [
                                                "change" => "function() {
                                                    if($(this).val() != '' && $(this).val() != null){
                                                        location.replace(baseUrl + \"/kiosk/calling/blooddrill-room?secid=\" + $(this).val());
                                                    }else{
                                                        location.replace(baseUrl + \"/kiosk/calling/blooddrill-room?secid=null\");
                                                    }
                                                }",
                                            ]
                                        ]);
                                        ?>
                                    </div>

                                    <div class="col-md-4">
                                        <?= $form->field($model, 'counter')->widget(DepDrop::classname(), [
                                            'data'=> $model->dataCounterBd,
                                            'options' => ['placeholder'=>'เลือกเคาน์เตอร์...'],
                                            'pluginOptions'=>[
                                                'depends'=>['callingform-section'],
                                                'placeholder'=>'เลือกเคาน์เตอร์...',
                                                'url'=>Url::to(['sub-section']),
                                            ],
                                            'type'=>DepDrop::TYPE_SELECT2,
                                            'select2Options'=>[
                                                'pluginOptions'=>['allowClear'=>true],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                                'size' => Select2::LARGE,
                                                'options' => ['placeholder' => 'เลือกเคาน์เตอร์...'],
                                                'pluginEvents' => [
                                                    "change" => "function() {
                                                        var section = $('#callingform-section').val();
                                                        var counter = $(this).val();
                                                        if(counter !== null && section !== null){
                                                            location.replace(baseUrl + \"/kiosk/calling/blooddrill-room?secid=\" + section + \"&counterid=\" + $(this).val());
                                                        }else{
                                                            location.replace(baseUrl + \"/kiosk/calling/blooddrill-room?secid=null&counterid=null\");
                                                        }
                                                    }",
                                                ]
                                            ],
                                            
                                        ]);
                                        ?>
                                    </div>

                                    <div class="col-md-4">
                                        <?= $form->field($model,'qnum')->textInput([
                                            'class' => 'input-lg',
                                            'placeholder' => 'คีย์หมายเลขคิวที่นี่เพื่อเรียก',
                                            'style' => 'background-color: #434a54;color: white;'
                                        ])->hint(''); ?>
                                    </div>
                                </div>
                            </div><!-- End panel body -->
                        </div><!-- End hpanel -->
                        <?php ActiveForm::end() ?>

                        <div class="hpanel">
                            <?php
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => 'กำลังเรียก '. Html::tag('span','0',['id' => 'count-calling','class' => 'badge']),
                                        'active' => true,
                                        'options' => ['id' => 'tab-calling'],
                                        'linkOptions' => ['style' => 'font-size: 14px;','class' => 'tabx'],
                                        'headerOptions' => ['class' => 'tab-calling']
                                    ],
                                    [
                                        'label' => 'พักคิว '. Html::tag('span','0',['id' => 'count-hold','class' => 'badge']),
                                        'options' => ['id' => 'tab-hold'],
                                        'linkOptions' => ['style' => 'font-size: 14px;'],
                                        'headerOptions' => ['class' => 'tab-hold']
                                    ],
                                ],
                                'options' => ['class' => 'nav nav-tabs'],
                                'encodeLabels' => false,
                                'renderTabContent' => false,
                            ]);
                            ?>
                            <div class="tab-content">
                                <div id="tab-calling" class="tab-pane active">
                                    <div class="panel-body">
                                        <?php  
                                        echo Table::widget([
                                            'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed table-striped','width' => '100%','id' => 'tb-calling'],
                                            //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                            'beforeHeader' => [
                                                [
                                                    'columns' => [
                                                        ['content' => '#','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'หมายเลขคิว','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'HN','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ชื่อ-นามสกุล','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ประเภท','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ช่องบริการ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'เวลามาถึง','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'สถานะ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
                                                    ]
                                                ]
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                                <div id="tab-hold" class="tab-pane">
                                    <div class="panel-body">
                                        <?php  
                                        echo Table::widget([
                                            'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed table-striped','width' => '100%','id' => 'tb-hold'],
                                            //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                            'beforeHeader' => [
                                                [
                                                    'columns' => [
                                                        ['content' => '#','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'หมายเลขคิว','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'HN','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ชื่อ-นามสกุล','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ประเภท','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ช่องบริการ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'เวลามาถึง','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'สถานะ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
                                                    ]
                                                ]
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End hpanel -->

                        <div class="hpanel">
                            <?php
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => 'รอเรียกเจาะเลือด '. Html::tag('span','0',['id' => 'count-waiting','class' => 'badge']),
                                        'active' => true,
                                        'options' => ['id' => 'tab-watting'],
                                        'linkOptions' => ['style' => 'font-size: 14px;'],
                                    ],
                                ],
                                'options' => ['class' => 'nav nav-tabs'],
                                'encodeLabels' => false,
                                'renderTabContent' => false,
                            ]);
                            ?>
                            <div class="tab-content">
                                <div id="tab-watting" class="tab-pane active">
                                    <div class="panel-body" style="padding-buttom: 0px;">
                                        <?php  
                                        echo Table::widget([
                                            'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed table-striped','width' => '100%','id' => 'tb-waiting'],
                                            //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                            'beforeHeader' => [
                                                [
                                                    'columns' => [
                                                        ['content' => '#','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'หมายเลขคิว','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'HN','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ชื่อ-นามสกุล','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ประเภท','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ช่องบริการ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'เวลามาถึง','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'สถานะ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
                                                    ]
                                                ]
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End hpanel -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= Datatables::widget([
    'id' => 'tb-waiting',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/calling/data-tbwaiting-bd',
            'data' => [
                'secid' => $model['section'],
                'counter' => $model['counter'],
            ]
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
        "autoWidth" => false,
        "deferRender" => true,
        //"searching" => false,
        "searchHighlight" => true,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            dtFnc.initConfirm(api);
            var count  = api.data().count();
            $("#count-waiting").html(count);
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        'columns' => [
            ["data" => null,"defaultContent" => "", "className" => "dt-center dt-head-nowrap","title" => "#", "orderable" => false],
            ["data" => "q_num","className" => "dt-body-center dt-head-nowrap","title" => "<i class=\"fa fa-money\"></i> หมายเลขคิว"],
            ["data" => "q_hn","className" => "dt-body-center dt-head-nowrap","title" => "HN"],
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap","title" => "<i class=\"fa fa-user\"></i> ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "counter_service_id","className" => "dt-body-center dt-head-nowrap","title" => "ช่องบริการ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "<i class=\"fa fa-clock-o\"></i> เวลามาถึง"],
            ["data" => "service_status_name","className" => "dt-body-center dt-head-nowrap","title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "<i class=\"fa fa-cogs\"></i> ดำเนินการ"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>

<?= Datatables::widget([
    'id' => 'tb-calling',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/calling/data-tbcalling-bd',
            'data' => [
                'secid' => $model['section'],
                'counter' => $model['counter'],
            ]
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
        "autoWidth" => false,
        "deferRender" => true,
        //"searching" => false,
        "searchHighlight" => true,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            dtFnc.initConfirm(api);
            var count  = api.data().count();
            $("#count-calling").html(count);
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        'columns' => [
            ["data" => null,"defaultContent" => "", "className" => "dt-center dt-head-nowrap","title" => "#", "orderable" => false],
            ["data" => "q_num","className" => "dt-body-center dt-head-nowrap","title" => "<i class=\"fa fa-money\"></i> หมายเลขคิว"],
            ["data" => "q_hn","className" => "dt-body-center dt-head-nowrap","title" => "HN"],
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap","title" => "<i class=\"fa fa-user\"></i> ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "counterservice_name","className" => "dt-body-center dt-head-nowrap","title" => "ช่องบริการ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "<i class=\"fa fa-clock-o\"></i> เวลามาถึง"],
            ["data" => "service_status_name","className" => "dt-body-center dt-head-nowrap","title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "<i class=\"fa fa-cogs\"></i> ดำเนินการ"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>

<?= Datatables::widget([
    'id' => 'tb-hold',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/calling/data-tbhold-bd',
            'data' => [
                'secid' => $model['section'],
                'counter' => $model['counter'],
            ]
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
        "autoWidth" => false,
        "deferRender" => true,
        //"searching" => false,
        "searchHighlight" => true,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            dtFnc.initConfirm(api);
            var count  = api.data().count();
            $("#count-hold").html(count);
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        'columns' => [
            ["data" => null,"defaultContent" => "", "className" => "dt-center dt-head-nowrap","title" => "#", "orderable" => false],
            ["data" => "q_num","className" => "dt-body-center dt-head-nowrap","title" => "<i class=\"fa fa-money\"></i> หมายเลขคิว"],
            ["data" => "q_hn","className" => "dt-body-center dt-head-nowrap","title" => "HN"],
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap","title" => "<i class=\"fa fa-user\"></i> ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "counterservice_name","className" => "dt-body-center dt-head-nowrap","title" => "ช่องบริการ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "<i class=\"fa fa-clock-o\"></i> เวลามาถึง"],
            ["data" => "service_status_name","className" => "dt-body-center dt-head-nowrap","title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "<i class=\"fa fa-cogs\"></i> ดำเนินการ"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>

<!-- jPlayer -->
<div id="jplayer_notify"></div>

<?php
$this->registerJs(<<<JS
//dt highlight
dt_tbcalling.on( 'draw', function () {
    var body = $( dt_tbcalling.table().body() );

    body.unhighlight();
    body.highlight( dt_tbcalling.search() );  
} );
dt_tbhold.on( 'draw', function () {
    var body = $( dt_tbhold.table().body() );

    body.unhighlight();
    body.highlight( dt_tbhold.search() );  
} );
dt_tbwaiting.on( 'draw', function () {
    var body = $( dt_tbwaiting.table().body() );

    body.unhighlight();
    body.highlight( dt_tbwaiting.search() );  
} );

//hidden menu
$('body').addClass('hide-sidebar');
$('input[type="search"]').removeClass('input-sm').addClass('input-lg');

//Socket Event
$(function() {
    socket
    .on('register', (res) => {//ลงทะเบียนผู้ป่วย /kiosk/register/index
        if(res.model.service_sec_id === model.section){
            qFunc.registerEvent(res);
        }
    });
});

// Toastr options
toastr.options = {
    "debug": false,
    "newestOnTop": false,
    "positionClass": "toast-top-center",
    "closeButton": true,
    "toastClass": "animated fadeInDown",
};

qFunc = {
    registerEvent:function(res){
        var player = $("#jplayer_notify").jPlayer({
            ready: function () {
                $(this).jPlayer("setMedia", {
                    mp3: "/media/alert.mp3",
                }).jPlayer("play");
            },
            supplied: "mp3",
            ended: function() { // The $.jPlayer.event.ended event
                $(this).jPlayer("stop"); // Repeat the media
            },
        });
        $('#jplayer_notify').jPlayer("play");
        dt_tbwaiting.ajax.reload();//โหลดข้อมูลรอเรียกใหม่
        toastr.warning(res.modelQ.pt_name, 'ผู้ป่วยลงทะเบียนใหม่!', {timeOut: 3000,positionClass: "toast-top-right"});
    },
};

//เรียกคิวเจาะเลือด
$('#tb-waiting tbody').on( 'click', 'tr td a.btn-calling', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbwaiting.row( tr ).data();
    swal({
        title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
        text: data.pt_name,
        html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
        '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'เรียกคิว',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {//Confirm
            $.ajax({
                method: "POST",
                url: baseUrl + "/kiosk/calling/call-blooddrill-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm {section, counter}
                },
                success: function(res){
                    if(res.status == 200){
                        dt_tbwaiting.ajax.reload();//โหลดข้อมูลรอเรียกใหม่
                        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                        toastr.success('CALL ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right"});
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                        socket.emit('call-blooddrill-room', res);//sending data
                    }else{
                        swal({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด!!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    swal({
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});

//Recall
$('#tb-calling tbody').on( 'click', 'tr td a.btn-recall', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbcalling.row( tr ).data();
    swal({
        title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
        text: data.pt_name,
        html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
        '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'เรียกคิว',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {//Confirm
            $.ajax({
                method: "POST",
                url: baseUrl + "/kiosk/calling/recall-blooddrill-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm {section, counter}
                },
                success: function(res){
                    if(res.status == 200){
                        toastr.success('RECALL ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                        socket.emit('call-blooddrill-room', res);//sending data
                    }else{
                        swal({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด!!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    swal({
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});

//พักคิว
$('#tb-calling tbody').on( 'click', 'tr td a.btn-hold', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbcalling.row( tr ).data();
    swal({
        title: 'ยืนยันพักคิว '+data.qnumber+' ?',
        text: data.pt_name,
        html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
        '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'พักคิว',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {//Confirm
            $.ajax({
                method: "POST",
                url: baseUrl + "/kiosk/calling/hold-blooddrill-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm {section, counter}
                },
                success: function(res){
                    if(res.status == 200){//success
                        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
                        toastr.success('HOLD ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right"});
                        socket.emit('hold-blooddrill-room', res);//sending data
                    }else{//error
                        swal({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด!!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    swal({
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});

//End คิว
$('#tb-calling tbody').on( 'click', 'tr td a.btn-end', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbcalling.row( tr ).data();
    swal({
        title: 'ยืนยัน END คิว '+data.qnumber+' ?',
        text: data.pt_name,
        html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
        '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {//Confirm
            $.ajax({
                method: "POST",
                url: baseUrl + "/kiosk/calling/end-blooddrill-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm {section, counter}
                },
                success: function(res){
                    if(res.status == 200){//success
                        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
                        toastr.success('END ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right"});
                        socket.emit('endq-blooddrill-room', res);//sending data
                    }else{//error
                        swal({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด!!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    swal({
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});

//เรียกคิว hold
$('#tb-hold tbody').on( 'click', 'tr td a.btn-calling', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbhold.row( tr ).data();
    swal({
        title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
        text: data.pt_name,
        html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
        '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'เรียกคิว',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {//Confirm
            $.ajax({
                method: "POST",
                url: baseUrl + "/kiosk/calling/callhold-blooddrill-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm
                },
                success: function(res){
                    if(res.status == 200){//success
                        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
                        toastr.success('CALL ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                        socket.emit('call-blooddrill-room', res);//sending data
                    }else{//error
                        swal({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด!!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    swal({
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});

//End คิว hold
$('#tb-hold tbody').on( 'click', 'tr td a.btn-end', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbhold.row( tr ).data();
    swal({
        title: 'ยืนยัน End คิว '+data.qnumber+' ?',
        text: data.pt_name,
        html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
        '<p><i class="fa fa-user"></i>'+data.pt_name+'</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'ยืนยัน',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {//Confirm
            $.ajax({
                method: "POST",
                url: baseUrl + "/kiosk/calling/endhold-blooddrill-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm
                },
                success: function(res){
                    if(res.status == 200){//success
                        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
                        toastr.success('End ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                        socket.emit('endq-blooddrill-room', res);//sending data
                    }else{//error
                        swal({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด!!',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error:function(jqXHR, textStatus, errorThrown){
                    swal({
                        type: 'error',
                        title: errorThrown,
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        }
    });
});

//เช็คเคาน์เตอร์
var counter = $('#callingform-counter').val();
var section = $('#callingform-section').val();
if(counter == 0 && section != ''){
    $('#calling-form').yiiActiveForm('updateAttribute', 'callingform-counter', ['กรุณาเลือกเคาน์เตอร์']);
}

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
        //ข้อมูลรอเรียกคัดกรอง
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
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/kiosk/calling/recall-blooddrill-room",
                            dataType: "json",
                            data: {
                                data:qcall.data,//Data in column Datatable
                                model:model//Data Model CallingForm {section, counter}
                            },
                            success: function(res){
                                if(res.status == 200){
                                    toastr.success('RECALL ' + qcall.data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                                    socket.emit('call-blooddrill-room', res);//sending data
                                }else{
                                    swal({
                                        type: 'error',
                                        title: 'เกิดข้อผิดพลาด!!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                swal({
                                    type: 'error',
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            }else if(qcall.tbkey === 'tbhold'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/kiosk/calling/callhold-blooddrill-room",
                            dataType: "json",
                            data: {
                                data: qcall.data,//Data in column Datatable
                                model: model//Data Model CallingForm
                            },
                            success: function(res){
                                if(res.status == 200){//success
                                    dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                                    dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
                                    toastr.success('CALL ' + qcall.data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                                    socket.emit('call-blooddrill-room', res);//sending data
                                }else{//error
                                    swal({
                                        type: 'error',
                                        title: 'เกิดข้อผิดพลาด!!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                swal({
                                    type: 'error',
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            }else if(qcall.tbkey === 'tbwaiting'){
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    text: qcall.data.pt_name,
                    html: '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' + 
                    '<p><i class="fa fa-user"></i>'+qcall.data.pt_name+'</p>',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/kiosk/calling/call-blooddrill-room",
                            dataType: "json",
                            data: {
                                data: qcall.data,//Data in column Datatable
                                model: model//Data Model CallingForm {section, counter}
                            },
                            success: function(res){
                                if(res.status == 200){
                                    dt_tbwaiting.ajax.reload();//โหลดข้อมูลรอเรียกคัดกรองใหม่
                                    dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลัวเรียกใหม่
                                    toastr.success('CALL ' + qcall.data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                                    socket.emit('call-blooddrill-room', res);//sending data
                                }else{
                                    swal({
                                        type: 'error',
                                        title: 'เกิดข้อผิดพลาด!!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                swal({
                                    type: 'error',
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        });
                    }
                });
            }
        }
    }else{
        toastr.error(dataObj['CallingForm[qnum]'], 'ไม่พบข้อมูล!', {timeOut: 3000,positionClass: "toast-top-right"});
    }
    $('input#callingform-qnum').val(null);//clear data
    return false;
});
JS
);
?>