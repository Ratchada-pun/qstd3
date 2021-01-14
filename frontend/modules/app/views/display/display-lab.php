<?php
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Html;


$this->title = 'Display';

$this->registerCss($this->render('./css/display.css'));
$this->registerCss('
    body {
        background-color: '.$config['background_color'].';
    }
    table.table-display thead tr {
        width: 50%;
        border-radius: 15px;
        border: 5px solid white;
        background-color: '.$config['header_color'].';
        color: '.$config['font_color'].';
        font-weight: bold;
    }
    table.table-display tbody tr {
        width: 50%;
        border-radius: 15px;
        border: 5px solid white;
        background-color: '.$config['column_color'].';
        color: '.$config['font_cell_display_color'].';
        font-weight: bold;
    }
    table.table-display tbody tr td.td-left{
        border-top: 5px solid '.$config['border_color'].' !important;
        border-bottom: 5px solid '.$config['border_color'].' !important;
        border-left: 5px solid '.$config['border_color'].' !important;
        border-right: 5px solid '.$config['border_color'].' !important;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        border-top-right-radius: 10px;
    }
    table.table-display tbody tr td.td-right{
        border-top: 5px solid '.$config['border_color'].' !important;
        border-bottom: 5px solid '.$config['border_color'].' !important;
        border-right: 5px solid '.$config['border_color'].' !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    table.table-display thead tr th.th-right{
        border-top: 5px solid '.$config['border_color'].' !important;
        border-bottom: 5px solid '.$config['border_color'].' !important;
        border-right: 5px solid '.$config['border_color'].' !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    table.table-display thead tr th.th-left{
        border-top: 5px solid '.$config['border_color'].' !important;
        border-bottom: 5px solid '.$config['border_color'].' !important;
        border-left: 5px solid '.$config['border_color'].' !important;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
');
?>
<div class="container">
	<div class="row">
	    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align: left;">
	        <h1 class="text-success" style="color: #62cb31;"><?= Html::encode('รอผลตรวจ/วินิจฉัย') ?></h1>
	    </div>
	</div>
	<div class="row">
	    <div class="col-xs-6 col-sm-6 col-md-6">
	        <table class="table table-display" id="table-display">
	        	<tbody>
	        	</tbody> 
	        </table>
	    </div>
        <div class="col-xs-6 col-sm-6 col-md-6">
            <table class="table table-display" id="table-display2">
                <tbody>
                </tbody> 
            </table>
        </div>
	</div>

</div>
<?php
#Table Display
echo Datatables::widget([
    'id' => 'table-display',
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/display/data-displaylab',
            'type' => 'GET',
            'data' => ['id' => $config['display_ids']]
        ],
        "dom" => "t",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => !empty($config['display_limit']) ? $config['display_limit'] : 5,
        "autoWidth" => false,
        "deferRender" => true,
        "ordering" => false,
        "info" => false,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            $(settings.nTHead).hide();
            setTimeout(function(){
                api.ajax.reload();
                dt_tabledisplay2.ajax.reload();
            }, 1000 * 60);
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
            }
        '),
        'columns' => [
            [
            	"data" => "pt_name", 
            	"className" => "dt-left dt-head-nowrap th-left td-left",
            	"title" => '', 
            	"orderable" => false
            ],
            [
            	"data" => "vn", 
            	"className" => "dt-body-left dt-head-nowrap th-right td-right",
            	"title" => '',
            	"orderable" => false
            ],
        ],
        "columnDefs" => [
            [ "visible" => false, "targets" => 1 ]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            //swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\'});
            console.warn(message);
        }'
    ]
]);

echo Datatables::widget([
    'id' => 'table-display2',
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/display/data-displaylab2',
            'type' => 'GET',
            'data' => ['id' => $config['display_ids']]
        ],
        "dom" => "t",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => !empty($config['display_limit']) ? $config['display_limit'] : 5,
        "autoWidth" => false,
        "deferRender" => true,
        "ordering" => false,
        "info" => false,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            $(settings.nTHead).hide();
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
            }
        '),
        'columns' => [
            [
                "data" => "pt_name", 
                "className" => "dt-left dt-head-nowrap th-left td-left",
                "title" => '', 
                "orderable" => false
            ],
            [
                "data" => "vn", 
                "className" => "dt-body-left dt-head-nowrap th-right td-right",
                "title" => '',
                "orderable" => false
            ],
        ],
        "columnDefs" => [
            [ "visible" => false, "targets" => 1 ]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            //swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\'});
            console.warn(message);
        }'
    ]
]);
?>
