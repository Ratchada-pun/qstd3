<?php
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\bootstrap\Modal;
use homer\assets\SweetAlert2Asset;
use yii\icons\Icon;
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\Url;

SweetAlert2Asset::register($this);

$this->title  = 'ออกบัตรคิว';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss(<<<CSS
.modal-header {
    padding: 10px;
}
.kv-feedback-default {
    color: white !important;
}
.kv-feedback-success {
    color: white !important;
}
.kv-feedback-error {
    color: white !important;
}
.form-control-static {
    font-size: 15px;
}
@media (max-width: 991px){
    #row-search {
            margin-bottom: 10px;
        }
    }
CSS
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="hpanel">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => $this->title,
                        'active' => true,
                        'options' => [
                            'id' => 'tab-1',
                        ]
                    ],
                ],
                'encodeLabels' => false,
                'renderTabContent' => false
            ]);
            ?>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'search-form',
                            'type' => 'horizontal',
                            'options' => ['autocomplete' => 'off'],
                            'action' => 'search-hn'
                        ]) ?>
                        <div class="hpanel">
                            <div class="panel-body" style="border: 1px dashed #dee5e7;">
                                <div class="form-group" style="margin-bottom: 0px;margin-top: 15px;">
                                    <div class="col-md-9">
                                        <?= $form->field($model, 'hn',[
                                            'feedbackIcon' => [
                                                'default' => 'search',
                                                'success' => 'ok',
                                                'error' => 'remove',
                                            ],
                                            'showLabels'=>false
                                        ])->textInput([
                                            'placeholder' => 'HN หรือ เลขที่บัตร ปชช.',
                                            'class' => 'form-control input-lg',
                                            'autofocus' => true,
                                            'style' => 'background-color: #434a54;color: white;'
                                        ])->label(false) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= Html::resetButton(Icon::show('remove').'Reset',['class' => 'btn btn-danger btn-lg','title' => 'รีเซ็ต']); ?>
                                        <?= Html::submitButton(Icon::show('search').'ค้นหา', ['class' => 'btn btn-success btn-lg','title' => 'ค้นหา']) ?>
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
                                'tableOptions' => ['class' => 'table table-hover table-bordered table-condensed','width' => '100%','id' => 'tb-qdata'],
                                'beforeHeader' => [
                                    [
                                        'columns' => [
                                            ['content' => '#','options' => ['style' => 'text-align: center;']],
                                            ['content' => 'หมายเลขคิว','options' => ['style' => 'text-align: center;']],
                                            ['content' => 'HN','options' => ['style' => 'text-align: center;']],
                                            ['content' => 'ชื่อ-นามสกุล','options' => ['style' => 'text-align: center;']],
                                            ['content' => 'ประเภท','options' => ['style' => 'text-align: center;']],
                                            ['content' => 'แผนก','options' => ['style' => 'text-align: center;']],
                                            ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
                                        ]
                                    ]
                                ],
                            ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
Modal::begin([
    'header' => '<h4 class="modal-title"><i class="pe-7s-note2"></i> บันทึกข้อมูลบัตรคิว</h4>',
    //'size' => 'modal-lg',
    'id' => 'modal-his',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false]
]);

echo '<div id="form-result"></div>';

Modal::end();
?>

<?= Datatables::widget([
    'id' => 'tb-qdata',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/default/data-q'
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
            ["data" => "pt_name","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อ-นามสกุล"],
            ["data" => "pt_visit_type","className" => "dt-body-center dt-head-nowrap","title" => "ประเภท"],
            ["data" => "sec_name","className" => "dt-body-center dt-head-nowrap","title" => "แผนก"],
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

<?php
$this->registerJs(<<<JS
var \$form = $('#search-form');
var \$modal = $("#modal-his");
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('form#search-form button[type="submit"]').button('loading');
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            if(data.status == 'duplicate'){
                swal({
                    title: "ลงทะเบียนแล้ว!!",
                    type: 'warning',
                    html: data.message,
                    showConfirmButton: true,
                    animation: false,
                    customClass: 'animated shake',
                    allowOutsideClick: false,
                });
                \$btn.button('reset');
            }else if(data.status === 200){
                $("#form-result").html(data.form);
                $(\$modal).modal('show');
                \$btn.button('reset');
            }else{
                swal({
                    type: 'warning',
                    title: "ไม่พบข้อมูล",
                    showConfirmButton: false,
                    timer: 1000
                });
                \$btn.button('reset');
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

//Reset Form
$(\$modal).on('hidden.bs.modal', function (e) {
    $(\$form).trigger("reset");
});
//search data
$('input#search').on( 'keyup', function () {
    dt_tbqdata.search( this.value ).draw();
});
JS
);
?>