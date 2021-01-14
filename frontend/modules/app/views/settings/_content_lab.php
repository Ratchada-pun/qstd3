<?php
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
?>
<div class="panel-body">
    <div class="table-responsive">
    <?php  
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-lab'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#','options' => []],
                    ['content' => 'Lab Code','options' => []],
                    ['content' => 'Lab Name','options' => []],
                    ['content' => 'Confirm','options' => []],
                    ['content' => 'ดำเนินการ','options' => []],
                ]
            ]
        ],
    ]);
    ?>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'tb-lab',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/settings/data-lab'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('download').' นำเข้าข้อมูล Lab', false,['class' => 'btn btn-success activity-import-lab']),
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
        "autoWidth" => false,
        "deferRender" => true,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            dtFnc.initConfirm(api);
            $("#tb-lab tbody tr td input.activity-lab-confirm").on("click",function(){
                saveLab(this);
            });
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        'columns' => [
            ["data" => null,"defaultContent" => "", "className" => "dt-center ","title" => "#", "orderable" => false],
            ["data" => "lab_items_code","className" => "dt-body-left ","title" => "Lab Code"],
            ["data" => "lab_items_name","className" => "dt-body-left","title" => "Lab Name"],
            ["data" => "confirm","className" => "dt-body-center","title" => "Lab Confirm"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
        ],
        "columnDefs" => [
            [ "visible" => false, "targets" => [0,4] ]
        ]
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
    function saveLab(e){
        var confirm = '';
        if($(e).is(':checked')){
            confirm = 'Y';
        }else{
            confirm = 'N';
        }

        $.ajax({
            type: "POST",
            url: '/app/settings/save-lab',
            data: {code: $(e).data('key'),confirm: confirm},
            success: function(res){
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            dataType: 'JSON',
            error: function( jqXHR,  textStatus,  errorThrown){
                swal('Oops...',errorThrown,'error');
            }
        });
    }

    $('a.activity-import-lab').on('click',function(){
        swal({
            title: 'ต้องการนำเข้าข้อมูล Lab หรือไม่?',
            text: " ",
            html:
            'ระบบจะนำเข้ามูล Lab จากฐานข้อมูล HOSxP <br>' +
            '<small class="text-danger">**เฉพาะข้อมูลที่ยังไม่มีในระบบคิวเท่านั้น</small>',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'นำเข้า',
            cancelButtonText: 'ยกเลิก',
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve, reject) {
                    $.ajax({
                        type: "POST",
                        url: '/app/settings/import-lab',
                        success: function(res){
                            if(res != 0){
                                swal({//alert completed!
                                    type: 'success',
                                    title: 'นำเข้า Lab ใหม่ ' + res + ' รายการ',
                                    showConfirmButton: true,
                                });
                                dt_tblab.ajax.reload();
                            }else{
                                swal({//alert completed!
                                    type: 'success',
                                    title: 'ไม่พบข้อมูล Lab ใหม่',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                            //resolve();
                        },
                        dataType: 'JSON',
                        error: function( jqXHR,  textStatus,  errorThrown){
                            swal('Oops...',errorThrown,'error');
                        }
                    });
                });
            },
        }).then((result) => {
            if (result.value) {
            }
        });
        
    });
JS
);
?>