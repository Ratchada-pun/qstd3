<?php
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DepDrop;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Tabs;
use yii\web\View;
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use frontend\modules\kiosk\models\TbSection;
use frontend\modules\kiosk\models\TbCounterservice;
use homer\assets\ICheckAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\SocketIOAsset;
use homer\assets\jPlayerAsset;
use homer\assets\ToastrAsset;

ICheckAsset::register($this);
SweetAlert2Asset::register($this);
SocketIOAsset::register($this);
jPlayerAsset::register($this);
ToastrAsset::register($this);

$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
$this->registerJs('var model = '.Json::encode($model).'; ',View::POS_HEAD);
$this->registerJs('var dataCounter = '.Json::encode(ArrayHelper::map(TbCounterservice::find()->asArray()->all(),'counterserviceid','counterservice_name')).'; ',View::POS_HEAD);
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
.select2-dropdown {
    z-index: 9999;
}
table.dataTable tbody tr td {
    font-size: 14px;
}
table.dataTable thead tr th {
    font-size: 14px;
}
.radio-inline, .checkbox-inline {
    font-size: 14px;
}
/* input[type="search"] { 
    background-color: #434a54;
    color: white;
} */
table.dataTable span.highlight {
    background-color: #f0ad4e;
    color: white;
}
/* .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    background-color: #74d348;
} */
CSS
);

$this->title  = 'เรียกคิวห้องตรวจ';
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
                                            'data' => ArrayHelper::map(TbSection::find()->where(['sec_id' => 1])->asArray()->all(),'sec_id','sec_name'),
                                            'options' => ['placeholder' => 'เลือกแผนก...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'size' => Select2::LARGE,
                                            'pluginEvents' => [
                                                "change" => "function() {
                                                    if($(this).val() != '' && $(this).val() != null){
                                                        location.replace(baseUrl + \"/kiosk/calling/examination-room?secid=\" + $(this).val());
                                                    }else{
                                                        location.replace(baseUrl + \"/kiosk/calling/examination-room?secid=null\");
                                                    }
                                                }",
                                            ]
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
                                <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'counter')->checkboxList($model->dataCounterserviceEx,[
                                            'inline'=>true,
                                            'class' => 'i-checks'
                                        ]) ?>
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
                                                        ['content' => 'ห้องตรวจ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'เวลามาถึง','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'สถานะ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
                                                    ]
                                                ]
                                            ],
                                        ]);
                                        ?>
                                    </div><!-- End panel body -->
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
                                                        ['content' => 'ห้องตรวจ','options' => ['style' => 'text-align: center;']],
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
                            </div><!-- End panel body -->
                        </div><!-- End hpanel -->

                        <div class="hpanel">
                            <?php
                            echo Tabs::widget([
                                'items' => [
                                    [
                                        'label' => 'คิวรอพบแพทย์ '. Html::tag('span','0',['id' => 'count-waiting','class' => 'badge']),
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
                                                        ['content' => 'ห้องตรวจ','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'เวลามาถึง','options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ผล LAB','options' => ['style' => 'text-align: center;']],
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
            </div><!-- End tabcontent -->
        </div>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'tb-waiting',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/calling/data-tbwaiting-ex',
            'data' => [
                'secid' => $model['section'],
                'counter' => $model['counter'],
            ],
            "type" => "POST"
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
            ["data" => "q_num","className" => "dt-body-center dt-head-nowrap","title" => "หมายเลขคิว"],
            ["data" => "q_hn","className" => "dt-body-center dt-head-nowrap","title" => "HN"],
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "counterservice_name","className" => "dt-body-center dt-head-nowrap","title" => "ห้องตรวจ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "เวลามาถึง"],
            ["data" => null,"defaultContent" => "","className" => "dt-body-center dt-head-nowrap","title" => "ผล LAB"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
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
            'url' => Url::base(true).'/kiosk/calling/data-tbcalling-ex',
            'data' => [
                'secid' => $model['section'],
                'counter' => $model['counter'],
            ],
            "type" => "POST"
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
            ["data" => "counterservice_name","className" => "dt-body-center dt-head-nowrap","title" => "ห้องตรวจ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "เวลามาถึง"],
            ["data" => "service_status_name","className" => "dt-body-center dt-head-nowrap","title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
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
            'url' => Url::base(true).'/kiosk/calling/data-tbhold-ex',
            'data' => [
                'secid' => $model['section'],
                'counter' => $model['counter'],
            ],
            "type" => "POST"
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
            ["data" => "q_num","className" => "dt-body-center dt-head-nowrap","title" => "หมายเลขคิว"],
            ["data" => "q_hn","className" => "dt-body-center dt-head-nowrap","title" => "HN"],
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "counterservice_name","className" => "dt-body-center dt-head-nowrap","title" => "ห้องตรวจ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "เวลามาถึง"],
            ["data" => "service_status_name","className" => "dt-body-center dt-head-nowrap","title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
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
<!-- modal -->
<?php echo $this->render('modal'); ?>

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

$('body').addClass('hide-sidebar');
$('input[type="search"]').removeClass('input-sm').addClass('input-lg');
// Initialize iCheck plugin
var input = $('.i-checks').iCheck({
    checkboxClass: 'icheckbox_flat-green',
    radioClass: 'iradio_square-green'
});
//socket event
$(function() {
    socket
    .on('endq-screening-room', (res) => {//คิวใหม่จากห้องคัดกรอง /kiosk/calling/screening-room
        qFunc.registerEvent(res);
    })
    .on('call-examination-room', (res) => {//เรียกคิวรอพบแพทย์ /kiosk/calling/examination-room
        if(res.eventOn === 'tb-waiting' && res.state === 'call'){//เรียกคิวจาก table คิวรอพบแพทย์
            dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
            dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
            swal.close();
        }else if(res.eventOn === 'tb-hold' && res.state === 'call-hold'){//เรียกคิวจาก table พักคิว
            dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
            dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
        }
    })
    .on('hold-examination-room', (res) => {//Hold คิวห้องตรวจ /kiosk/calling/examination-room
        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
    })
    .on('endq-examination-room', (res) => {//จบ Process q /kiosk/calling/examination-room
        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
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
        dt_tbwaiting.ajax.reload();//โหลดข้อมูลรอเรียกคัดกรองใหม่
        toastr.warning(res.modelQ.pt_name, 'ผู้ป่วยใหม่!', {timeOut: 3000,positionClass: "toast-top-right"});
    },
};

//Checkbox Event
$(input).on('ifChecked', function(event){
    var key = $(this).val();
    var \$form = $('#calling-form');
    var data = \$form.serialize();
    $.ajax({
        method: "POST",
        url: "/kiosk/calling/setcounter-value",
        data: data,
        dataType: "json",
        success: function(res){
            location.reload();
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
});

$(input).on('ifUnchecked', function(event){
    var key = $(this).val();
    var \$form = $('#calling-form');
    var data = \$form.serialize();
    $.ajax({
        method: "POST",
        url: "/kiosk/calling/setcounter-value",
        data: data,
        dataType: "json",
        success: function(res){
            location.reload();
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
});

//เรียกคิวรอพบแพทย์
$('#tb-waiting tbody').on( 'click', 'tr td a.btn-calling', function (event) {
    event.preventDefault();
    var tr = $(this).closest("tr");
    var key = tr.data("key");
    var data = dt_tbwaiting.row( tr ).data();
    var dataSelect = {};
    $.each(input, function( index, value ) {
        var pk = $(value).val();
        dataSelect[pk] = dataCounter[pk];
    });
    swal({
        title: 'ยืนยันเรียกคิว '+data.qnumber+' ?',
        html: data.pt_name + '<br>',
        input: 'select',
        type: 'warning',
        inputOptions: dataSelect,
        inputPlaceholder: 'เลือกห้องตรวจ',
        inputValue: data.counter_service_id || '',
        inputClass: 'form-control m-b',
        showCancelButton: true,
        confirmButtonText: 'เรียกคิว',
        cancelButtonText: 'ยกเลิก',
        allowOutsideClick: false,
        inputValidator: (value) => {
            return new Promise((resolve) => {
                if (value !== '') {
                    $.ajax({
                        method: "POST",
                        url: baseUrl + "/kiosk/calling/call-examination-room",
                        dataType: "json",
                        data: {
                            data:data,//Data in column Datatable
                            model:model,//Data Model CallingForm {section, counter}
                            value:value //select value
                        },
                        success: function(res){
                            if(res.status == 200){
                                dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                                toastr.success('CALL ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                                socket.emit('call-examination-room', res);//sending data
                                resolve();
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
                } else {
                    resolve('คุณไม่ได้เลือกห้องตรวจ!')
                }
            });
        }
    });
    jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th"});
    $('select.swal2-select, span.select2').addClass('input-lg');
    $('#swal2-content').css('padding-bottom','15px');
});

//เรียกคิวซ้ำ
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
                url: baseUrl + "/kiosk/calling/recall-examination-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm {section, counter}
                },
                success: function(res){
                    if(res.status == 200){
                        toastr.success('RECALL ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                        socket.emit('call-examination-room', res);//sending data
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
                url: baseUrl + "/kiosk/calling/hold-examination-room",
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
                        socket.emit('hold-examination-room', res);//sending data
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
                url: baseUrl + "/kiosk/calling/callhold-examination-room",
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
                        socket.emit('call-examination-room', res);//sending data
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
                url: baseUrl + "/kiosk/calling/endhold-examination-room",
                dataType: "json",
                data: {
                    data:data,//Data in column Datatable
                    model:model//Data Model CallingForm
                },
                success: function(res){
                    if(res.status == 200){//success
                        dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                        dt_tbhold.ajax.reload();//โหลดข้อมูลพักคิวใหม่
                        toastr.success('END ' + data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                        socket.emit('endq-examination-room', res);//sending data
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
                url: baseUrl + "/kiosk/calling/end-examination-room",
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
                        socket.emit('endq-examination-room', res);//sending data
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
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {//Confirm
                        $.ajax({
                            method: "POST",
                            url: baseUrl + "/kiosk/calling/recall-examination-room",
                            dataType: "json",
                            data: {
                                data:qcall.data,//Data in column Datatable
                                model:model//Data Model CallingForm {section, counter}
                            },
                            success: function(res){
                                if(res.status == 200){
                                    toastr.success('RECALL ' + qcall.data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right",});
                                    socket.emit('call-examination-room', res);//sending data
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
                            url: baseUrl + "/kiosk/calling/callhold-examination-room",
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
                                    socket.emit('call-examination-room', res);//sending data
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
                var dataSelect = {};
                $.each(input, function( index, value ) {
                    var pk = $(value).val();
                    dataSelect[pk] = dataCounter[pk];
                });
                swal({
                    title: 'ยืนยันเรียกคิว '+qcall.data.qnumber+' ?',
                    html: qcall.data.pt_name + '<br>',
                    input: 'select',
                    type: 'warning',
                    inputOptions: dataSelect,
                    inputPlaceholder: 'เลือกห้องตรวจ',
                    inputValue: qcall.data.counter_service_id || '',
                    inputClass: 'form-control m-b',
                    showCancelButton: true,
                    confirmButtonText: 'เรียกคิว',
                    cancelButtonText: 'ยกเลิก',
                    allowOutsideClick: false,
                    inputValidator: (value) => {
                        return new Promise((resolve) => {
                            if (value !== '') {
                                $.ajax({
                                    method: "POST",
                                    url: baseUrl + "/kiosk/calling/call-examination-room",
                                    dataType: "json",
                                    data: {
                                        data: qcall.data,//Data in column Datatable
                                        model:model,//Data Model CallingForm {section, counter}
                                        value:value //select value
                                    },
                                    success: function(res){
                                        if(res.status == 200){
                                            dt_tbwaiting.ajax.reload();//โหลดข้อมูลคิวรอพบแพทย์ใหม่
                                            dt_tbcalling.ajax.reload();//โหลดข้อมูลกำลังเรียกใหม่
                                            toastr.success('CALL ' + qcall.data.qnumber, 'Success!', {timeOut: 3000,positionClass: "toast-top-right"});
                                            socket.emit('call-examination-room', res);//sending data
                                            resolve();
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
                            } else {
                                resolve('คุณไม่ได้เลือกห้องตรวจ!')
                            }
                        });
                    }
                });
                jQuery('.swal2-select').select2({"allowClear":true,"theme":"bootstrap","width":"100%","placeholder":"เลือกห้องตรวจ...","language":"th"});
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
JS
);
?>