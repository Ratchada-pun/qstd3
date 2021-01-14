<?php
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use frontend\modules\kiosk\models\TbSection;
use yii\icons\Icon;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use homer\assets\SweetAlert2Asset;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;

$this->title  = 'ลงทะเบียนคิวผู้ป่วย';
$this->params['breadcrumbs'][] = $this->title;

SweetAlert2Asset::register($this);
ToastrAsset::register($this);
SocketIOAsset::register($this);
$this->registerJs('var baseUrl = '.Json::encode(Url::base(true)).'; ',View::POS_HEAD);
$this->registerJs('var model = '.Json::encode($model).'; ',View::POS_HEAD);

$this->registerCss(<<<CSS
    @media (max-width: 991px){
        #row-search {
            margin-bottom: 10px;
        }
    }
    td.dt-name {
        font-weight: bold;
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
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12" style="">
        <div class="hpanel">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => '<i class="pe-7s-id"></i> '.$this->title,
                        'active' => true,
                        'options' => ['id' => 'tab-1'],
                    ],
                ],
                'options' => ['class' => 'nav nav-tabs'],
                'encodeLabels' => false,
                'renderTabContent' => false,
            ]);
            ?>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'register-form',
                            'type' => 'horizontal',
                            'options' => ['autocomplete' => 'off'],
                            'formConfig' => ['showLabels' => false],
                            'action' => Url::to(['check-register'])
                        ]) ?>
                        <div class="hpanel">
                            <div class="panel-body" style="border: 1px dashed #dee5e7;">
                                <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                    <div class="col-md-4">
                                        <?=
                                        $form->field($model, 'section')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(TbSection::find()->asArray()->all(),'sec_id','sec_name'),
                                            'options' => ['placeholder' => 'เลือกแผนก...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'size' => Select2::LARGE,
                                            'pluginEvents' => [
                                                "change" => "function() {
                                                    if($(this).val() != '' && $(this).val() != null){
                                                        location.replace(baseUrl + \"/kiosk/register/index?secid=\" + $(this).val());
                                                    }else{
                                                        location.replace(baseUrl + \"/kiosk/register/index?secid=null\");
                                                    }
                                                }",
                                            ]
                                        ]);
                                        ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'barcode',[
                                            'feedbackIcon' => [
                                                'default' => 'search',
                                                'success' => 'ok',
                                                'error' => 'remove',
                                            ],
                                            'showLabels'=>false
                                        ])->textInput([
                                            'placeholder' => 'Scan Barcode',
                                            'class' => 'form-control input-lg',
                                            'autofocus' => true,
                                            'style' => 'background-color: #434a54;color: white;'
                                        ])->label(false) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= Html::submitButton(Icon::show('check-square-o').'ลงทะเบียน', ['class' => 'btn btn-success btn-lg','title' => 'ลงทะเบียน']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end() ?>
                        <div class="row" id="row-search">
                            <div class="col-md-6">
                                <input type="text" class="form-control input-lg" name="search" id="search" placeholder="ค้นหาข้อมูล">
                            </div>
                        </div>
                        <?php  
                            echo Table::widget([
                                'tableOptions' => ['class' => 'table table-hover table-bordered table-striped','width' => '100%','id' => 'tb-qregister'],
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
                                        ]
                                    ]
                                ],
                            ]);
                        ?>
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <strong>Donec quam felis</strong>

                        <p>Thousand unknown plants are noticed by me: when I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms of the insects
                            and flies, then I feel the presence of the Almighty, who formed us in his own image, and the breath </p>

                        <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite
                            sense of mere tranquil existence, that I neglect my talents. I should be incapable of drawing a single stroke at the present moment; and yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'tb-qregister',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/register/data-register',
            'data' => [
                'secid' => $model['section']
            ]
        ],
        "dom" => "<'pull-right'l>t<'pull-left'i>p",
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
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap dt-name","title" => "ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "counter_service_id","className" => "dt-body-center dt-head-nowrap","title" => "ห้องตรวจ"],
            ["data" => "checkin_date","className" => "dt-body-center dt-head-nowrap","title" => "เวลามาถึง"],
            ["data" => "service_status_name","className" => "dt-body-center dt-head-nowrap","title" => "สถานะ"],
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>

<?php
$this->registerJs(<<<JS
//dt highlight
dt_tbqregister.on( 'draw', function () {
    var body = $( dt_tbqregister.table().body() );

    body.unhighlight();
    body.highlight( dt_tbqregister.search() );  
} );

var \$form = $('#register-form');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();//เก็บข้อมูลจากฟอร์ม
    var \$btn = $('form#register-form button[type="submit"]').button('loading');
    $.ajax({//ตรวจสอบข้อมูลการลงทะเบียน
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            if(data.status == 'duplicate'){//ลงทะเบียนแล้ว
                swal({//แจ้งเตือน
                    title: "ลงทะเบียนแล้ว!!",
                    type: 'warning',
                    html: data.message,
                    showConfirmButton: true,
                    animation: false,
                    customClass: 'animated shake',
                    allowOutsideClick: false,
                });
                \$btn.button('reset');
            }else if(data.status == 'no data'){//ไม่พบข้อมูล
                swal({//แจ้งเตือน
                    type: 'warning',
                    title: "ไม่พบข้อมูล",
                    showConfirmButton: false,
                    timer: 1000
                });
                \$btn.button('reset');
            }else{
                swal({//ยืนยันการลงทะเบียน
                    title: 'ยืนยันการลงทะเบียน?',
                    text: data.model.pt_name,//ชื่อผู้ป่วย
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({//ลงทะเบียน
                            method: "GET",
                            url: "/kiosk/register/insert-register",
                            data: {q_ids: data.model.q_ids, secid: data.secid},
                            dataType: "json",
                            success: function(result){
                                if(result.status == '200'){//ลงทะเบียนสำเร็จ
                                    dt_tbqregister.ajax.reload();//รีโหลดข้อมุลใหม่
                                    $('input#registerform-barcode').val("");//clear value
                                    swal({
                                        type: 'success',
                                        title: 'ลงทะเบียนสำเร็จ!',
                                        showConfirmButton: false,
                                        timer: 1000
                                    });
                                    socket.emit('register', result);//sending data
                                }else{//ลงทะเบียนไม่สำเร็จ
                                    swal({
                                        type: 'error',
                                        title: "เกิดข้อผิดพลาด!!",
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                \$btn.button('reset');
                            },
                            error:function(jqXHR, textStatus, errorThrown){
                                swal({
                                    type: 'error',
                                    title: errorThrown,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                \$btn.button('reset');
                            }
                        });
                    }else{
                        \$btn.button('reset');
                    }
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            swal({
                type: 'error',
                title: errorThrown,
                showConfirmButton: false,
                timer: 1500
            });
            \$btn.button('reset');
        }
    });
    return false; // prevent default submit
});
//search data
$('input#search').on( 'keyup', function () {
    dt_tbqregister.search( this.value ).draw();
});
JS
);
?>