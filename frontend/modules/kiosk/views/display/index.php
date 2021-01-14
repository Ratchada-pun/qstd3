<?php
use frontend\assets\ModernBlinkAsset;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Html;

ModernBlinkAsset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);

$this->title = 'เรียกคิวคัดกรอง';

$this->registerCss($this->render('./css/display.css'));
$this->registerCss('
    body {
            background-color: '.$config['background_color'].';
    }
    table thead tr {
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
        color: '.$config['font_color'].';
        font-weight: bold;
    }
    table.table-hold tbody tr td.td-hold-left{
        width: 50%;
        border-top: 5px solid '.$config['border_color'].' !important;
        border-bottom: 5px solid '.$config['border_color'].' !important;
        border-right: 5px solid '.$config['border_color'].' !important;
        border-left: 5px solid '.$config['border_color'].' !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        background-color: '.$config['column_color'].';
        color: '.$config['font_color'].';
        vertical-align: middle;
    }
    table.table-display tbody tr td.td-left{
        border-top: 5px solid '.$config['border_color'].' !important;
        border-bottom: 5px solid '.$config['border_color'].' !important;
        border-left: 5px solid '.$config['border_color'].' !important;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
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
$this->registerJs('var counter = '.Json::encode($counter).'; ',View::POS_HEAD);
$this->registerJs('var config = '.Json::encode($config).'; ',View::POS_HEAD);
?>
<div class="container">
	<div class="row">
	    <div class="col-xs-6 col-sm-6 col-md-6 border-right" style="text-align: center;">
	        <h1 class="text-success" style="color: <?= $config['title_color']; ?>"><?= Html::encode($config['title_left']) ?></h1>
	    </div>
	    <div class="col-xs-6 col-sm-6 col-md-6" style="text-align: center;">
	        <h1 class="text-success" style="color: <?= $config['title_color']; ?>"><?= Html::encode($config['title_right']) ?></h1>
	    </div>
	</div>
	<div class="row">
	    <div class="col-xs-12 col-sm-12 col-md-12">
	        <table class="table table-display" id="table-display"> 
	        	<thead> 
	        		<tr> 
	        			<th style="width: 50%;" class="th-left"><?= Html::encode($config['table_title_left']) ?></th> 
	        			<th style="width: 50%;" class="th-right"><?= Html::encode($config['table_title_right']) ?></th> 
	        		</tr> 
	        	</thead> 
	        	<tbody>
	        	</tbody> 
	        </table>

	        <table class="table table-hold" id="table-hold"> 
	        	<tbody> 
	        		<tr> 
	        			<td class="td-hold-left"><?= Html::encode($config['hold_label']) ?></td> 
	        			<td class="td-hold-right"></td> 
	        		</tr>
	        	</tbody> 
	        </table>
	    </div>
	</div>

</div>
<?php
#Table Display
echo Datatables::widget([
    'id' => 'table-display',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/display/data-display',
            'data' => [
            	'config' => $config
            ],
            'type' => 'POST'
        ],
        "dom" => "t",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => -1,
        "autoWidth" => false,
        "deferRender" => true,
        "ordering" => false,
        "info" => false,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
            }
        '),
        'columns' => [
            [
            	"data" => "q_num", 
            	"className" => "dt-center dt-head-nowrap th-left td-left",
            	"title" => $config['table_title_left'], 
            	"orderable" => false
            ],
            [
            	"data" => "service_number", 
            	"className" => "dt-body-center dt-head-nowrap th-right td-right",
            	"title" => $config['table_title_right'],
            	"orderable" => false
            ],
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\'});
        }'
    ]
]);


#Table Hold
echo Datatables::widget([
    'id' => 'table-hold',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/kiosk/display/data-display-hold',
            'data' => [
            	'config' => $config
            ],
            'type' => 'POST'
        ],
        "dom" => "t",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "searchPlaceholder" => "ค้นหา...",

        ]),
        "pageLength" => -1,
        "autoWidth" => false,
        "deferRender" => true,
        "ordering" => false,
        "info" => false,
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                $(".table-hold thead").hide();
            }
        '),
        'columns' => [
            ["data" => null,"defaultContent" => "คิวที่เรียกไปแล้ว","className" => "dt-center dt-head-nowrap td-hold-left", "orderable" => false],
            ["data" => "q_num", "className" => "dt-body-center dt-head-nowrap td-hold-right"],
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\'});
        }'
    ]
]);
?>
<?php
$this->registerJs(<<<JS
socket
.on('display', (res) => {
	if(res.artist.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tabledisplay.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		setTimeout(function(){
			if(undefined != dt_tabledisplay.row( '#' + res.artist.data.caller_ids ).data()){
				
			}else{
				dt_tabledisplay.row( ':eq(0)' ).remove();
				dt_tabledisplay.row.add( {
			        "q_num": '<text class="'+res.title+'">' + res.title + '</text>',
			        "service_number": '<text class="'+res.title+'">' + res.artist.counter.sound_service_number + '</text>',
			    } ).draw().node();
			}
			Display.blink(res);
		}, 500);
	}
})
.on('hold-screening-room', (res) => {
	if(res.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tabledisplay.row( '#' + res.data.caller_ids ).remove().draw();
	}
})
.on('endq-screening-room', (res) => {
	if(res.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tabledisplay.row( '#' + res.data.caller_ids ).remove().draw();
	}
})
.on('hold-examination-room', (res) => {
	if(res.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tabledisplay.row( '#' + res.data.caller_ids ).remove().draw();
	}
})
.on('endq-examination-room', (res) => {
	if(res.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tabledisplay.row( '#' + res.data.caller_ids ).remove().draw();
	}
})
.on('hold-blooddrill-room', (res) => {
	if(res.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tabledisplay.row( '#' + res.data.caller_ids ).remove().draw();
	}
})
.on('endq-blooddrill-room', (res) => {
	if(res.counter.counterservice_type == counter.tb_counterservice_typeid){
		dt_tablehold.ajax.reload();//โหลดข้อมูลแสดงผล
		dt_tabledisplay.row( '#' + res.data.caller_ids ).remove().draw();
	}
});

Display = {
	blink: function(res){//สั่งกระพริบ
		$("td.td-left text, td.td-right text").removeClass("modernBlink");
		$('td.td-left text.' + res.title + ', td.td-right text.'+ res.title).modernBlink({
			duration: 1000,
			iterationCount: 7,
			auto: true
		});
		$("text." + res.title).addClass("modernBlink");
	}
};

JS
);
?>