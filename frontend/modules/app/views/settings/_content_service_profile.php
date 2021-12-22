<?php
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
?>
<div class="panel-body">
    <?php  
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-service-profile'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#','options' => []],
                    ['content' => 'ID','options' => []],
                    ['content' => 'ชื่อบริการ','options' => []],
                    ['content' => 'Counter','options' => []],
                    ['content' => 'Service','options' => []],
                   // ['content' => 'Service status','options' => []],
                    ['content' => 'สถานะ','options' => []],
                    ['content' => 'ดำเนินการ','options' => []],
                ]
            ]
        ],
    ]);
    ?>
</div>

<?= Datatables::widget([
    'id' => 'tb-service-profile',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/settings/data-service-profile'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่ม-ลบ รายการ', ['/app/settings/create-service-profile'],['class' => 'btn btn-success','role' => 'modal-remote']),
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
            ["data" => null,"defaultContent" => "", "className" => "dt-center ","title" => "#", "orderable" => false],
            ["data" => "service_profile_id","className" => "dt-body-left ","title" => "ID"],
            ["data" => "service_name","className" => "dt-body-left ","title" => "ชื่อบริการ"],
            ["data" => "counterservice_type","className" => "dt-body-left dt-nowrap","title" => "Counter"],
            ["data" => "servicelist","className" => "dt-body-left ","title" => "Service"],
           // ["data" => "service_status_id","className" => "dt-body-left ","title" => "สถานะคิว"],
            ["data" => "service_profile_status","className" => "dt-center ","title" => "สถานะ"],
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
// $this->registerJsFile(
//     '//cdn.jsdelivr.net/npm/sweetalert2@11',
//     ['depends' => [\yii\web\JqueryAsset::class]]
// );

$js = <<<JS
    $('#tb-service-profile tbody').on('click', 'tr td a.btn-copy-service-profile', function(e){
        e.preventDefault()
        var url = $(this).attr('href');
        swal({
            title: 'ยืนยันการคัดลอก?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Copy',
            showLoaderOnConfirm: true,
            // didOpen: () => {
            //     $(Swal.getIcon()).css('fontSize', '12px')
            // },
            preConfirm: function() {
                return new Promise(function(resolve) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        dataType: "json",
                        success: function (res) {
                            var table = $('#tb-service-profile').DataTable();
                            table.ajax.reload();//reload table
                            resolve()
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorThrown,
                            })
                        },
                    });
                })
            }
        }).then((result) => {
            if (result.value) {
                
            }
        })
    });
JS;

$this->registerJs($js);
?>