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
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-counter'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#','options' => []],
                    ['content' => 'ชื่อบริการ','options' => []],
                    ['content' => 'หมายเลข','options' => []],
                    ['content' => 'ประเภท','options' => []],
                    ['content' => 'กลุ่มบริการ','options' => []],
                    ['content' => 'กลุ่มบริการย่อย','options' => []],
                    ['content' => 'เสียงบริการ','options' => []],
                    ['content' => 'เสียงหมายเลข','options' => []],
                    ['content' => 'สถานะ','options' => []],
                    ['content' => 'ดำเนินการ','options' => []],
                ]
            ]
        ],
    ]);
    ?>
</div>

<?= Datatables::widget([
    'id' => 'tb-counter',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/settings/data-counter'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่ม-ลบ รายการ', ['/app/settings/create-counter'],['class' => 'btn btn-success','role' => 'modal-remote']),
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
        "autoWidth" => false,
        "deferRender" => true,
        //"order" => [[ 1, "asc" ]],
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            var rows = api.rows( {page:"current"} ).nodes();
            var columns = api.columns().nodes();
            var last=null;
            api.column(3, {page:"current"} ).data().each( function ( group, i ) {
                var data = api.rows(i).data();
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        \'<tr class="warning"><td colspan="\'+columns.length+\'">\'+ group + \' <a class="btn btn-xs btn-success" role="modal-remote" href="/app/settings/service-order?id=\'+ data[0].counterservice_type_id + \'">จัดเรียงลำดับ</a>\' +
                        \'</td></tr>\'
                    );
                    last = group;
                }
            } );
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
            ["data" => "counterservice_name","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อบริการ"],
            ["data" => "counterservice_callnumber","className" => "dt-body-left dt-head-nowrap","title" => "หมายเลข"],
            ["data" => "counterservice_type","className" => "dt-body-left dt-head-nowrap","title" => "ประเภท"],
            ["data" => "servicegroup_name","className" => "dt-body-left dt-head-nowrap","title" => "กลุ่มบริการ"],
            ["data" => "service_name","className" => "dt-body-left dt-head-nowrap","title" => "กลุ่มบริการย่อย"],
            ["data" => "sound_service_name","className" => "dt-body-left dt-head-nowrap","title" => "เสียงบริการ"],
            ["data" => "sound_name","className" => "dt-body-left dt-head-nowrap","title" => "เสียงหมายเลข"],
            ["data" => "counterservice_status","className" => "dt-center dt-nowrap","orderable" => false,"title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
        ],
        "columnDefs" => [
            ["visible" => false, "targets" => 3],
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>