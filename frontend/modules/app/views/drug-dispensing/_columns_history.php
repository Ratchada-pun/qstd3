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
                'tableOptions' => ['class' => 'table table-hover', 'width' => '100%', 'id' => 'tb-drug-dispensing2'],
                'beforeHeader' => [
                    [
                        'columns' => [
                            ['content' => '#', 'options' => []],
                            ['content' => 'เลขที่ใบส่งยา', 'options' => []],
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
            ]);
            ?>
        </div>
</div>

<?= Datatables::widget([
    'id' => 'tb-drug-dispensing2',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true) . '/app/drug-dispensing/data-drug-history'
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

        // "drawCallback" => new JsExpression ('function(settings) {
        //     var api = this.api();
        //     var rows = api.rows( {page:"current"} ).nodes();
        //     var columns = api.columns().nodes();
        //     var last=null;
        //     api.column(1, {page:"current"} ).data().each( function ( group, i ) {
        //         var data = api.rows(i).data();
        //         if ( last !== group ) {
        //             $(rows).eq( i ).before(
        //                 \'<tr class="warning"><td colspan="\'+columns.length+\'">\'+group+\' <a href="/app/settings/update-service-group?id=\'+data[0].service_groupid+\'" class="btn btn-xs btn-success" role="modal-remote"><i class="fa fa-plus"></i></a> </td></tr>\'
        //             );
        //             last = group;
        //         }
        //     } );
        //     dtFnc.initConfirm(api);
        // }'),
        'initComplete' => new JsExpression('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        // "columnDefs" => [
        //     ["visible" => false, "targets" => 1],
        // ],
        'columns' => [
            ["data" => null, "defaultContent" => "", "className" => "dt-center dt-head-nowrap", "title" => "#", "orderable" => false],
            ["data" => "rx_operator_id", "className" => "dt-body-left dt-head-nowrap", "title" => "เลขที่ใบส่งยา"],
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