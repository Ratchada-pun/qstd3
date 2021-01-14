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
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-display'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => 'ID','options' => []],
                    ['content' => 'Display Name','options' => []],
                    ['content' => 'Counter','options' => []],
                    ['content' => 'Service','options' => []],
                    ['content' => 'Title Left','options' => []],
                    ['content' => 'Title Right','options' => []],
                    ['content' => 'Title Color','options' => []],
                    ['content' => 'Header Left','options' => []],
                    ['content' => 'Heeader Right','options' => []],
                    ['content' => 'Display Limit','options' => []],
                    ['content' => 'Hold Label','options' => []],
                    ['content' => 'Header Color','options' => []],
                    ['content' => 'Column Color','options' => []],
                    ['content' => 'Background Color','options' => []],
                    ['content' => 'Font Color','options' => []],
                    ['content' => 'Boder Color','options' => []],
                    ['content' => 'ดำเนินการ','options' => []],
                ]
            ]
        ],
    ]);
    ?>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'tb-display',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/settings/data-display'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่ม-ลบ รายการ', ['/app/settings/create-display'],['class' => 'btn btn-success','role' => 'modal-remote']),
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
            ["data" => "display_name","className" => "dt-body-left ","title" => "Display Name"],
            ["data" => "counterservice_id","className" => "dt-body-left dt-nowrap","title" => "Counter"],
            ["data" => "service_id","className" => "dt-body-left dt-nowrap","title" => "Service"],
            ["data" => "title_left","className" => "dt-body-left ","title" => "Title Left"],
            ["data" => "title_right","className" => "dt-body-left ","title" => "Title Right"],
            ["data" => "title_color","className" => "dt-body-left ","title" => "Title Color"],
            ["data" => "table_title_left","className" => "dt-body-left ","title" => "Header Left"],
            ["data" => "table_title_right","className" => "dt-body-left ","title" => "Header Right"],
            ["data" => "display_limit","className" => "dt-body-left ","title" => "Display Limit"],
            ["data" => "hold_label","className" => "dt-body-left ","title" => "Hold Label"],
            ["data" => "header_color","className" => "dt-body-left ","title" => "Header Color"],
            ["data" => "column_color","className" => "dt-body-left ","title" => "Column Color"],
            ["data" => "background_color","className" => "dt-body-left ","title" => "Background Color"],
            ["data" => "font_color","className" => "dt-body-left ","title" => "Font Color"],
            ["data" => "border_color","className" => "dt-body-left ","title" => "Boder Color"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
        ],
        "columnDefs" => [
            [ "visible" => false, "targets" => [4,5,6,7,8,9,10,11,12,13,14,15] ]
        ]
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>