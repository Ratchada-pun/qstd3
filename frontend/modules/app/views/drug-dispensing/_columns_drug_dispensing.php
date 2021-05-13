<?php

use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\select2\Select2;
?>

<div class="panel-body">
    <div class="table-responsive">
        <?php
        echo Table::widget([
            'tableOptions' => ['class' => 'table table-hover', 'width' => '100%', 'id' => 'tb-drug-dispensing'],
            'beforeHeader' => [
                [
                    'columns' => [
                        ['content' => '#', 'options' => []],
                        ['content' => 'เลขที่ใบส่งยา', 'options' => ['rowspan' => 2]],
                        ['content' => 'ชื่อร้านขายยา', 'options' => []],
                        ['content' => 'HN', 'options' => []],
                        ['content' => 'ชื่อผู้รับบริการ', 'options' => []],
                        ['content' => 'วันที่สั่งยา', 'options' => []],
                        ['content' => 'แพทย์ผู้สั่งยา', 'options' => []],
                        ['content' => 'วันที่จ่ายยา', 'options' => []],
                        ['content' => 'สถานะ', 'options' => []],
                        ['content' => 'Action', 'options' => []],
                    ]
                ]
            ],
            'afterHeader' => [
                [
                    'columns' => [
                        ['content' => '', 'options' => ['colspan' => 2]],
                        ['content' => 'ชื่อร้าน', 'options' => []],
                        ['content' => '', 'options' => ['colspan' => 7]],
                    ]
                ]
            ],
        ]);
        ?>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'tb-drug-dispensing',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true) . '/app/drug-dispensing/data-drug-dispensing'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'], [
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        "autoWidth" => false,
        "deferRender" => true,
        'initComplete' => new JsExpression('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );

                api.columns().every( function (index) {
                    //var column = this;
                    if(index === 2){
                        var column = api.column( index );
                        var select = $(\'<select class="form-control"><option value="">ทั้งหมด</option></select>\')
                            .appendTo( $(column.header()).empty() )
                            .on( \'change\', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? \'^\'+val+\'$\' : \'\', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( \'<option value="\'+d+\'">\'+d+\'</option>\' )
                        } );
                    }
                    
                } );
            }
        '),
        // "columnDefs" => [
        //     ["visible" => false, "targets" => 1],
        // ],
        'columns' => [
            ["data" => null, "defaultContent" => "", "className" => "dt-center dt-head-nowrap", "title" => "#", "orderable" => false],
            ["data" => "rx_operator_id", "className" => "dt-body-left dt-head-nowrap", "title" => "เลขที่ใบส่งยา"],
            ["data" => "pharmacy_drug_name", "className" => "dt-body-left dt-head-nowrap","orderable" => false],
            ["data" => "HN", "className" => "dt-body-left dt-head-nowrap", "title" => "HN"],
            ["data" => "pt_name", "className" => "dt-body-left dt-head-nowrap", "title" => "ชื่อผู้รับบริการ"],
            ["data" => "prescription_date", "className" => "dt-body-left dt-head-nowrap", "title" => "วันที่สั่งยา"],
            ["data" => "doctor_name", "className" => "dt-body-left dt-head-nowrap", "title" => "แพทย์ผู้สั่งยา"],
            ["data" => "dispensing_date", "className" => "dt-body-left dt-head-nowrap", "title" => "วันที่จ่ายยา"],
            ["data" => "dispensing_status_des", "className" => "dt-body-left dt-head-nowrap", "title" => "สถานะ"],
            ["data" => "actions", "className" => "dt-center dt-nowrap", "orderable" => false, "title" => "Action"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>