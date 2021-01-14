<?php
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\icons\Icon;

$this->registerCss(<<<CSS
@media (max-width: 991px){
    #row-search {
            margin-bottom: 10px;
        }
    }
CSS
);
?>
<div class="panel-body">
	<!--<div class="row" id="row-search">
	    <div class="col-md-6">
	        <input type="text" class="form-control input-lg" name="search" id="search" placeholder="ค้นหาข้อมูล" style="background-color: #434a54;color: #ffffff;">
	    </div>
	</div>-->
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
                ['content' => 'สถานะ','options' => ['style' => 'text-align: center;']],
	            ['content' => 'ดำเนินการ','options' => ['style' => 'text-align: center;']],
	        ]
	    ]
	],
	]);
	?>
</div>

<?= Datatables::widget([
    'id' => 'tb-qdata',
    'select2' => true,
    'buttons' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/kiosk/data-q',
            "complete" => new JsExpression('function(jqXHR, textStatus){
                dt_tbqdata.button( 0 ).processing( false );
            }')
        ],
        "dom" => "<'row'<'col-xs-6'f><'col-xs-6'lB>> <'row'<'col-xs-12'tr>> <'row'<'col-xs-5'i><'col-xs-7'p>>",
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
            var count  = api.data().count();
            $("#count-qdata").html(count);
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
            ["data" => "service_name","className" => "dt-body-left dt-head-nowrap","title" => "ประเภท"],
            ["data" => "status","className" => "dt-body-left dt-head-nowrap","title" => "สถานะ"],
            ["data" => "actions","className" => "dt-center dt-nowrap","orderable" => false,"title" => "ดำเนินการ"]
        ],
        "buttons" => [
            [
                'text' => Icon::show('refresh', [], Icon::BSG).' Reload',
                'action' => new JsExpression ('function ( e, dt, node, config ) {
                    this.processing( true );
                    dt.ajax.reload();
                }'),
            ]
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
//search data
$('input#search').on( 'keyup', function () {
    dt_tbqdata.search( this.value ).draw();
});
JS
);
?>