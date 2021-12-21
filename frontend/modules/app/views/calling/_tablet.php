<?php

use frontend\modules\app\models\TbCounterservice;
use frontend\modules\app\models\TbServiceProfile;
use homer\widgets\Datatables;
use homer\widgets\Table;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use homer\assets\SocketIOAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use yii\bootstrap\Tabs;
use yii\helpers\Json;
use yii\web\View;

SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);

$this->registerJs('var keySelected = [];', View::POS_HEAD);
$this->registerJs('var baseUrl = ' . Json::encode(Url::base(true)) . '; ', View::POS_HEAD);
$this->registerJs('var modelForm = ' . Json::encode($modelForm) . '; ', View::POS_HEAD);
$this->registerJs('var modelProfile = ' . Json::encode($modelProfile) . '; ', View::POS_HEAD);
$this->registerJs('var select2Data = ' . Json::encode(ArrayHelper::map(TbCounterservice::find()->where(['counterservice_status' => 1])->orderBy(['service_order' => SORT_ASC])->all(), 'counterserviceid', 'counterservice_name')) . '; ', View::POS_HEAD);

$this->title = 'เรียกคิว';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
html.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown), body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
    overflow-y: unset !important;
}
.footer {
    display: none;
}
.form-group {
    margin-bottom: 5px;
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
@media screen and (max-width: 640px){
    div.dt-buttons {
        float: right !important;
    }
}
table tbody tr td span.badge{
    font-size: 20px !important;
}
CSS;

$this->registerCss($css);
?>
<div class="row">
    <div class="col-lg-12">
        <div class="hpanel">
            <div class="panel-heading hbuilt">
                <h3><?= Html::encode($this->title) ?></h3> 
            </div>
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin([
                    'id' => 'calling-form',
                    'type' => 'horizontal',
                    'options' => ['autocomplete' => 'off'],
                    'formConfig' => ['showLabels' => false],
                ]) ?>
                <div class="form-group row" style="margin-bottom: 0;">
                    <div class="col-md-6">
                        <?=
                        $form->field($modelForm, 'service_profile')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(TbServiceProfile::find()->where(['service_profile_status' => 1])->all(), 'service_profile_id', 'service_name'),
                            'options' => ['placeholder' => 'เลือกช่องบริการ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::LARGE,
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6 counter_service" style="display: none;">
                        <?=
                        $form->field($modelForm, 'counter_service')->widget(Select2::classname(), [
                            'data' => $modelForm->dataCounter,
                            'options' => ['placeholder' => 'เลือก...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                            'theme' => Select2::THEME_BOOTSTRAP,
                            'size' => Select2::LARGE,
                        ]);
                        ?>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-12">
                        <?php foreach ($services as $key => $service) { ?>
                            <span class="label label-primary" style="margin-right: 5px;">
                                <?php echo $service['service_name'] ?>
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <?php ActiveForm::end() ?>

                <div class="row">
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <?php
                        echo Tabs::widget([
                            'items' => [
                                [
                                    'label' => 'คิวรอ (<span class="count-waiting">0</span>)',
                                    'active' => true,
                                    'headerOptions' => [],
                                    'options' => ['id' => 'tab-1'],
                                    'linkOptions' => [
                                        'style' => 'font-size: 2rem;'
                                    ]
                                ],
                                [
                                    'label' => 'คิวพัก (<span class="count-hold">0</span>)',
                                    'headerOptions' => [],
                                    'options' => ['id' => 'tab-2'],
                                    'linkOptions' => [
                                        'style' => 'font-size: 2rem;'
                                    ]
                                ],
                            ],
                            'renderTabContent' => false,
                            'encodeLabels' => false,

                        ]);
                        ?>
                        <div class="tab-content">
                            <div id="tab-1" class="tab-pane active">
                                <div class="hpanel">
                                    <div class="panel-body" style="padding: 10px;">
                                        <?php
                                        echo Table::widget([
                                            'tableOptions' => ['class' => 'table table-striped table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-waiting'],
                                            //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                            'beforeHeader' => [
                                                [
                                                    'columns' => [
                                                        ['content' => 'คิว', 'options' => ['style' => 'text-align: center; font-size: 12pt;']],
                                                        ['content' => 'ชื่อ', 'options' => ['style' => 'text-align: center;font-size: 12pt;']],
                                                        ['content' => 'บริการ', 'options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;width: 35px;white-space: nowrap;']],
                                                    ],
                                                    'options' => ['style' => 'background-color:cornsilk;'],
                                                ]
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div id="tab-2" class="tab-pane ">
                                <div class="hpanel">
                                    <div class="panel-body" style="padding: 10px;">
                                        <?php
                                        echo Table::widget([
                                            'tableOptions' => ['class' => 'table table-striped table-hover table-bordered table-condensed', 'width' => '100%', 'id' => 'tb-hold'],
                                            //'caption' => Html::tag('span','ลงทะเบียนแล้ว',['class' => 'badge badge-success']),
                                            'beforeHeader' => [
                                                [
                                                    'columns' => [
                                                        ['content' => 'คิว', 'options' => ['style' => 'text-align: center;font-size: 12pt;']],
                                                        ['content' => 'ชื่อ', 'options' => ['style' => 'text-align: center;font-size: 12pt;']],
                                                        ['content' => 'บริการ', 'options' => ['style' => 'text-align: center;']],
                                                        ['content' => 'ดำเนินการ', 'options' => ['style' => 'text-align: center;width: 35px;white-space: nowrap;font-size: 12pt;']],
                                                    ],
                                                    'options' => ['style' => 'background-color:cornsilk;'],
                                                ]
                                            ],
                                        ]);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4">
                        <div class="hpanel">
                            <div class="panel-heading hbuilt">
                                <h3>
                                    เรียกคิว
                                </h3>
                            </div>
                            <div class="panel-body" style="padding: 10px;">
                                <form method="get" class="form-horizontal">
                                    <div class="col-sm-12">
                                        <input type="text" id="input_q_num" autofocus placeholder="เลขคิว" class="form-control input-lg m-b" style="border-color: #3f5872 !important;font-size: 16pt;font-weight: 600;">
                                    </div>
                                    <p class="text-center">
                                        <button style="width: 48%;text-transform: uppercase;" class="btn btn-warning activity-callnext" type="button"><i class="fa fa-arrow-right fa-2x" aria-hidden="true"></i> <br>คิวถัดไป</button>
                                        <button style="width: 48%;text-transform: uppercase;" class="btn btn-success btn-recall" type="button"><i class="fa fa-volume-up fa-2x" aria-hidden="true"></i> <br>เรียกคิว</button>

                                    </p>
                                    <p class="text-center">
                                        <button style="width: 48%;text-transform: uppercase;" class="btn btn-warning2 btn-hold" type="button"><i class="fa fa-hand-paper-o fa-2x" aria-hidden="true"></i> <br>พักคิว</button>
                                        <button style="width: 48%;text-transform: uppercase;" class="btn btn-info btn-finish" type="button"><i class="fa fa-check-square-o fa-2x" aria-hidden="true"></i> <br>จบคิว</button>
                                    </p>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php

echo Datatables::widget([
    'id' => 'tb-waiting',
    'buttons' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true) . '/app/calling/data-tbwaiting',
            'data' => ['modelForm' => $modelForm, 'modelProfile' => $modelProfile],
            "type" => "POST",
            "complete" => new JsExpression('function(jqXHR, textStatus){
            
            }')
        ],
        "dom" => "<'row'<'col-xs-6'f><'col-xs-6'l>> <'row'<'col-xs-12'tr>> <'row'<'col-xs-5'i><'col-xs-7'p>>",
        "language" => array_merge(Yii::$app->params['dtLanguage'], [
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา...",
            "lengthMenu" => "_MENU_"
        ]),
        "pageLength" => -1,
        "lengthMenu" => [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, 'All']],
        "autoWidth" => false,
        "ordering" => false,
        "deferRender" => true,
        //"searching" => false,
        "searchHighlight" => true,
        "responsive" => true,
        "drawCallback" => new JsExpression('function(settings) {
            var api = this.api();
            var count  = api.data().count();
            $(".count-waiting").html(count);

            var rows = api.rows( {page:"current"} ).nodes();
            var columns = api.columns().nodes();
            var last=null;
            api.column(2, {page:"current"} ).data().each( function ( group, i ) {
                var data = api.rows(i).data();
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        \'<tr class=""><td style="text-align: left;font-size:16px" colspan="\'+columns.length+\'">\'+group +\'</td></tr>\'
                    );
                    last = group;
                }
            } );
        }'),
        'initComplete' => new JsExpression('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
            }
        '),
        'columns' => [
            [
                "data" => "q_num",
                "className" => "dt-body-center dt-head-nowrap",
                "title" => "<i class=\"fa fa-money\"></i> คิว"
            ],
            [
                "data" => "pt_name",
                "className" => "dt-body-left dt-head-nowrap",
                "title" => "ชื่อ"
            ],
            [
                "data" => "service_name",
                "className" => "dt-body-left dt-head-nowrap",
                "title" => "บริการ",
                "visible" => false,
            ],
            [
                "data" => "actions",
                "className" => "dt-center dt-nowrap",
                "orderable" => false,
                "visible" => false,
                "title" => "<i class=\"fa fa-cogs\"></i> ดำเนินการ"
            ]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]);

echo Datatables::widget([
    'id' => 'tb-hold',
    'buttons' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true) . '/app/calling/data-tbhold',
            'data' => ['modelForm' => $modelForm, 'modelProfile' => $modelProfile],
            "type" => "POST"
        ],
        "dom" => "<'row'<'col-xs-6'f><'col-xs-6'l>> <'row'<'col-xs-12'tr>> <'row'<'col-xs-5'i><'col-xs-7'p>>",
        "language" => array_merge(Yii::$app->params['dtLanguage'], [
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา...",
            "lengthMenu" => "_MENU_"
        ]),
        "pageLength" => -1,
        "lengthMenu" => [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, 'All']],
        "autoWidth" => false,
        "deferRender" => true,
        "ordering" => false,
        //"searching" => false,
        "searchHighlight" => true,
        "responsive" => true,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            var count  = api.data().count();
            $(".count-hold").html(count);

            var rows = api.rows( {page:"current"} ).nodes();
            var columns = api.columns().nodes();
            var last=null;
            api.column(2, {page:"current"} ).data().each( function ( group, i ) {
                var data = api.rows(i).data();
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        \'<tr class=""><td style="text-align: left;font-size:16px" colspan="\'+columns.length+\'">\'+group +\'</td></tr>\'
                    );
                    last = group;
                }
            } );
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
            }
        '),
        'columns' => [
            [
                "data" => "q_num",
                "className" => "dt-body-center dt-head-nowrap",
                "title" => "<i class=\"fa fa-money\"></i> คิว"
            ],
            [
                "data" => "pt_name",
                "className" => "dt-body-left dt-head-nowrap",
                "title" => "ชื่อ"
            ],
            [
                "data" => "service_name",
                "className" => "dt-body-left dt-head-nowrap",
                "title" => "บริการ",
                "visible" => false,
            ],
            [
                "data" => "actions",
                "className" => "dt-center dt-nowrap",
                "orderable" => false,
                "title" => "<i class=\"fa fa-cogs\"></i> ดำเนินการ"
            ]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]);

$js = <<<JS
    $('#callingform-service_profile').on('change', function() {
        if($(this).val()){
            location.replace("/app/calling/call-tablet?profileid=" + $(this).val());
        } else {
            location.replace("/app/calling/call-tablet");
        }
    });

    function checkCounter() {
        if (modelForm.counter_service == null || modelForm.service_profile == null) {
            var title = modelForm.service_profile == null ? "กรุณาเลือกโปรไฟล์" : "กรุณาเลือกช่องบริการ";
            swal({
                type: "warning",
                title: title,
                showConfirmButton: false,
                timer: 1500,
            });
            return false;
        } else {
            return true;
        }
    }

    $("#tb-waiting tbody").on("click", "tr td a.btn-calling", function(event) {
        event.preventDefault();
        var tr = $(this).closest("tr"), url = $(this).attr("href");
        if (tr.hasClass("child") && typeof dt_tbwaiting.row(tr).data() === "undefined") {
            tr = $(this).closest("tr").prev();
        }
        var key = tr.data("key");
        var data = dt_tbwaiting.row(tr).data();
        var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
        if (checkCounter()) {
            swal({
                title: "ยืนยันเรียกคิว " + data.qnumber + " ?",
                text: data.pt_name,
                html:
                    '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                    "<p>" +
                    countername +
                    "</p>",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "เรียกคิว",
                cancelButtonText: "ยกเลิก",
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function(value) {
                    return new Promise(function(resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function(res) {
                                if (res.status == 200) {
                                    $('#input_q_num').val(data.qnumber)
                                    dt_tbwaiting.ajax.reload(); //โหลดข้อมูลคิวรอ
                                    toastr.success("CALL " + data.qnumber, "Success!", {
                                        timeOut: 3000,
                                        positionClass: "toast-top-right",
                                        progressBar: true,
                                        closeButton: true,
                                    });
                                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                                    socket.emit("call", res); //sending data
                                    resolve();
                                } else {
                                    swal({
                                        type: "error",
                                        title: "เกิดข้อผิดพลาด!!",
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                swal({
                                    type: "error",
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            },
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {
                    //Confirm
                }
            });
        }
    });


    //เรียกคิว hold
    $("#tb-hold tbody").on("click", "tr td a.btn-calling", function (event) {
        event.preventDefault();
        var tr = $(this).closest("tr"),
            url = $(this).attr("href");
        if (tr.hasClass("child") && typeof dt_tbhold.row(tr).data() === "undefined") {
            tr = $(this)
                .closest("tr")
                .prev();
        }
        var key = tr.data("key");
        var data = dt_tbhold.row(tr).data();
        var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
        if (checkCounter()) {
            swal({
                title: "ยืนยันเรียกคิว " + data.qnumber + " ?",
                text: data.pt_name,
                html:
                    '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                    "<p>" +
                    countername +
                    "</p>",
                type: "question",
                showCancelButton: true,
                confirmButtonText: "เรียกคิว",
                cancelButtonText: "ยกเลิก",
                allowOutsideClick: false,
                showLoaderOnConfirm: true,
                preConfirm: function () {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: {
                                data: data, //Data in column Datatable
                                modelForm: modelForm, //Data Model CallingForm
                                modelProfile: modelProfile,
                            },
                            success: function (res) {
                                if (res.status == 200) {
                                    $('#input_q_num').val(data.qnumber)
                                    dt_tbhold.ajax.reload(); //โหลดข้อมูลพักคิวใหม่
                                    toastr.success("CALL " + data.qnumber, "Success!", {
                                        timeOut: 3000,
                                        positionClass: "toast-top-right",
                                        progressBar: true,
                                        closeButton: true,
                                    });
                                    socket.emit("call", res); //sending data
                                    resolve();
                                } else {
                                    swal({
                                        type: "error",
                                        title: "เกิดข้อผิดพลาด!!",
                                        showConfirmButton: false,
                                        timer: 2000,
                                    });
                                }
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                swal({
                                    type: "error",
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500,
                                });
                            },
                        });
                    });
                },
            }).then((result) => {
                if (result.value) {
                    //Confirm
                    swal.close();
                }
            });
        }
    });

    $("button.activity-callnext").on("click", function (e) {
        e.preventDefault();
        $.ajax({
            method: "POST",
            url: '/app/calling/last-queue',
            dataType: "json",
            data: {
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
            },
            success: function (res) {
                if(res){
                    swal({
                        type: "warning",
                        title: "ไม่สามารถเรียกคิวถัดไปได้ กรุณากดจบคิวก่อนหน้า",
                        showConfirmButton: true,
                    });
                } else {
                    var data = dt_tbwaiting.rows(0).data(),
                        url = $(this).attr("data-url");
                    var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
                    if (data.length > 0) {
                        if (checkCounter()) {
                            swal({
                                title: "CALL NEXT " + data[0].qnumber + " ?",
                                text: data[0].qnumber,
                                html:
                                    '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                                    "<p>" +
                                    countername +
                                    "</p>",
                                type: "question",
                                showCancelButton: true,
                                confirmButtonText: "เรียกคิว",
                                cancelButtonText: "ยกเลิก",
                                showLoaderOnConfirm: true,
                                preConfirm: function (value) {
                                    return new Promise(function (resolve, reject) {
                                        $.ajax({
                                            method: "POST",
                                            url: "/app/calling/call-screening-room",
                                            dataType: "json",
                                            data: {
                                                data: data[0], //Data in column Datatable
                                                modelForm: modelForm, //Data Model CallingForm
                                                modelProfile: modelProfile,
                                            },
                                            success: function (res) {
                                                if (res.status == 200) {
                                                    $('#input_q_num').val(data.q_num)
                                                    dt_tbwaiting.ajax.reload(); //โหลดข้อมูลคิวรอ
                                                    toastr.success("CALL " + data.qnumber, "Success!", {
                                                        timeOut: 3000,
                                                        positionClass: "toast-top-right",
                                                        progressBar: true,
                                                        closeButton: true,
                                                    });
                                                    //$("html, body").animate({ scrollTop: 0 }, "slow");
                                                    socket.emit("call", res); //sending data
                                                    resolve();
                                                } else {
                                                    swal({
                                                        type: "error",
                                                        title: "เกิดข้อผิดพลาด!!",
                                                        showConfirmButton: false,
                                                        timer: 2000,
                                                    });
                                                }
                                            },
                                            error: function (jqXHR, textStatus, errorThrown) {
                                                swal({
                                                    type: "error",
                                                    title: errorThrown,
                                                    showConfirmButton: false,
                                                    timer: 1500,
                                                });
                                            },
                                        });
                                    });
                                },
                            }).then((result) => {
                                if (result.value) {
                                    swal.close();
                                }
                            });
                        }
                    } else {
                        swal({
                            type: "warning",
                            title: "ไม่พบหมายเลขคิว",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: "error",
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
        });
        
    });

    $("button.btn-recall").on("click",  function(event) {
        var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
        $.ajax({
            method: "POST",
            url: '/app/calling/last-queue',
            dataType: "json",
            data: {
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
            },
            success: function (data) {
                if(data){
                    swal({
                        title: "ยืนยันเรียกคิว " + data.q_num + " ?",
                        text: data.pt_name,
                        html:
                            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                            "<p>" +
                            countername +
                            "</p>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonText: "เรียกคิว",
                        cancelButtonText: "ยกเลิก",
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        preConfirm: function () {
                            return new Promise(function (resolve, reject) {
                                $.ajax({
                                    method: "POST",
                                    url: "/app/calling/recall?id=" + data.caller_ids,
                                    dataType: "json",
                                    data: {
                                        data: data, //Data in column Datatable
                                        modelForm: modelForm, //Data Model CallingForm
                                        modelProfile: modelProfile,
                                    },
                                    success: function (res) {
                                        if (res.status == 200) {
                                            $('#input_q_num').val(data.q_num)
                                            dt_tbwaiting.ajax.reload(); //โหลดข้อมูลคิวรอ
                                            dt_tbhold.ajax.reload(); //โหลดข้อมูลพักคิวใหม่
                                            toastr.success("CALL " + data.q_num, "Success!", {
                                                timeOut: 3000,
                                                positionClass: "toast-top-right",
                                                progressBar: true,
                                                closeButton: true,
                                            });
                                            socket.emit("call", res); //sending data
                                            resolve();
                                        } else {
                                            swal({
                                                type: "error",
                                                title: "เกิดข้อผิดพลาด!!",
                                                showConfirmButton: false,
                                                timer: 2000,
                                            });
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        swal({
                                            type: "error",
                                            title: errorThrown,
                                            showConfirmButton: false,
                                            timer: 1500,
                                        });
                                    },
                                });
                            });
                        },
                    }).then((result) => {
                        if (result.value) {
                            //Confirm
                            swal.close();
                        }
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: "error",
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
        });
    });

    $("button.btn-hold").on("click",  function(event) {
        var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
        $.ajax({
            method: "POST",
            url: '/app/calling/last-queue',
            dataType: "json",
            data: {
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
            },
            success: function (data) {
                if(data){
                    swal({
                        title: "ยืนยันพักคิว " + data.q_num + " ?",
                        text: data.pt_name,
                        html:
                            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                            "<p>" +
                            countername +
                            "</p>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonText: "พักคิว",
                        cancelButtonText: "ยกเลิก",
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        preConfirm: function () {
                            return new Promise(function (resolve, reject) {
                                $.ajax({
                                    method: "POST",
                                    url: '/app/calling/hold?id=' + data.caller_ids,
                                    dataType: "json",
                                    data: {
                                        data: data, //Data in column Datatable
                                        modelForm: modelForm, //Data Model CallingForm
                                        modelProfile: modelProfile,
                                    },
                                    success: function (res) {
                                        if (res.status == 200) {
                                            $('#input_q_num').val('')
                                            dt_tbwaiting.ajax.reload(); //โหลดข้อมูลคิวรอ
                                            dt_tbhold.ajax.reload(); //โหลดข้อมูลพักคิวใหม่
                                            toastr.success("HOLD " + data.q_num, "Success!", {
                                                timeOut: 3000,
                                                positionClass: "toast-top-right",
                                                progressBar: true,
                                                closeButton: true,
                                            });
                                            socket.emit("hold", res); //sending data
                                            resolve();
                                        } else {
                                            swal({
                                                type: "error",
                                                title: "เกิดข้อผิดพลาด!!",
                                                showConfirmButton: false,
                                                timer: 2000,
                                            });
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        swal({
                                            type: "error",
                                            title: errorThrown,
                                            showConfirmButton: false,
                                            timer: 1500,
                                        });
                                    },
                                });
                            });
                        },
                    }).then((result) => {
                        if (result.value) {
                            //Confirm
                            swal.close();
                        }
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: "error",
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
        });
    });

    $("button.btn-finish").on("click",  function(event) {
        var countername = $("#callingform-counter_service").select2("data")[0]["text"] || "";
        $.ajax({
            method: "POST",
            url: '/app/calling/last-queue',
            dataType: "json",
            data: {
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
            },
            success: function (data) {
                if(data){
                    swal({
                        title: "ยืนยัน END คิว " + data.q_num + " ?",
                        text: data.pt_name,
                        html:
                            '<small class="text-danger" style="font-size: 13px;">กด Enter เพื่อยืนยัน / กด Esc เพื่อยกเลิก</small>' +
                            '<p><i class="fa fa-user"></i>' +
                            data.pt_name +
                            "</p>" +
                            countername +
                            "</p>",
                        type: "question",
                        showCancelButton: true,
                        confirmButtonText: "ยืนยัน",
                        cancelButtonText: "ยกเลิก",
                        allowOutsideClick: false,
                        showLoaderOnConfirm: true,
                        preConfirm: function () {
                            return new Promise(function (resolve, reject) {
                                $.ajax({
                                    method: "POST",
                                    url: '/app/calling/end?id=' + data.caller_ids,
                                    dataType: "json",
                                    data: {
                                        data: data, //Data in column Datatable
                                        modelForm: modelForm, //Data Model CallingForm
                                        modelProfile: modelProfile,
                                    },
                                    success: function (res) {
                                        if (res.status == "200") {
                                            $('#input_q_num').val('')
                                            dt_tbwaiting.ajax.reload(); //โหลดข้อมูลคิวรอ
                                            dt_tbhold.ajax.reload(); //โหลดข้อมูลพักคิวใหม่
                                            toastr.success("END " + data.q_num, "Success!", {
                                                timeOut: 3000,
                                                positionClass: "toast-top-right",
                                                progressBar: true,
                                                closeButton: true,
                                            });
                                            socket.emit("finish", res); //sending data
                                            resolve();
                                        } else {
                                            swal({
                                                type: "error",
                                                title: "เกิดข้อผิดพลาด!!",
                                                showConfirmButton: false,
                                                timer: 2000,
                                            });
                                        }
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        swal({
                                            type: "error",
                                            title: errorThrown,
                                            showConfirmButton: false,
                                            timer: 1500,
                                        });
                                    },
                                });
                            });
                        },
                    }).then((result) => {
                        if (result.value) {
                            //Confirm
                            swal.close();
                        }
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: "error",
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
        });
    });

    function getLastQueue(){
        $.ajax({
            method: "POST",
            url: '/app/calling/last-queue',
            dataType: "json",
            data: {
                modelForm: modelForm, //Data Model CallingForm
                modelProfile: modelProfile,
            },
            success: function (res) {
                if(res){
                    $('#input_q_num').val(res.q_num)
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                swal({
                    type: "error",
                    title: errorThrown,
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
        });
    }

    getLastQueue();

    socket
    .on("register", (res) => {
        dt_tbwaiting.ajax.reload();
        toastr.warning(res.modelQueue.q_num, "คิวใหม่!", {
            timeOut: 7000,
            positionClass: "toast-top-right",
            progressBar: true,
            closeButton: true,
        });
    })
    .on("setting", (res) => {
        if (res.model === "service_profile") {
            dt_tbwaiting.ajax.reload();
        }
    })
    .on("call", (res) => {
        dt_tbwaiting.ajax.reload();
    })
    .on("hold", (res) => {
        dt_tbhold.ajax.reload(); //โหลดข้อมูลพักคิวใหม่
    })
JS;
$this->registerJs($js);
?>