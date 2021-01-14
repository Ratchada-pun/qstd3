<?php
use yii\helpers\Json;
use yii\web\View;
use homer\assets\SocketIOAsset;
use homer\assets\ToastrAsset;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Url;

SocketIOAsset::register($this);
ToastrAsset::register($this);

$this->registerCss('
body {
    background-color: '.$config['background_color'].';
}
');
$this->registerJs('var counter = '.Json::encode($counter).'; ',View::POS_HEAD);
$this->registerJs('var config = '.Json::encode($config).'; ',View::POS_HEAD);

$this->title = 'Display '.$config['display_name'];
?>
<style>
.container {
    width: auto;
}
@media (max-width: 768px) {
    table.table-display2 thead tr th {
        text-align: center;
        font-size: 30px;
    }

    table.table-display2 tbody tr td {
        text-align: center;
        font-size: 40px;
    }

    #marquee {
        font-size: 40px;
    }
    .text-header, td > button {
        font-size: 80px;
    }
}
/* Small devices (tablets, 768px and up) */
@media (min-width: 768px) {
    table.table-display2 thead tr th {
        text-align: center;
        font-size: 30px;
    }

    table.table-display2 tbody tr td {
        text-align: center;
        font-size: 40px;
    }

    #marquee {
        font-size: 40px;
    }

    .text-header, td > button {
        font-size: 100px;
    }
}
/* Medium devices (desktops, 992px and up) */
@media (min-width: 992px) {
    table.table-display2 thead tr th {
        text-align: center;
        font-size: 40px;
    }

    table.table-display2 tbody tr td {
        text-align: center;
        font-size: 50px;
    }

    #marquee {
        font-size: 50px;
    }

    .text-header, td > button {
        font-size: 130px;
    }
}
/* Large devices (large desktops, 1200px and up) */
@media (min-width: 1920px) {
    table.table-display2 thead tr th {
        text-align: center;
        font-size: 70px;
    }

    table.table-display2 tbody tr td {
        text-align: center;
        font-size: 80px;
    }

    #marquee {
        font-size: 80px;
    }
    .text-header, td > button {
        font-size: 160px;
    }
}
table {
    border-spacing: 5px;
    border-collapse: unset;
    border-spacing: 0 10px;
}
.width-fixed {
    width: 30%;
}

table tbody tr td {
    border-top: unset !important;
}

table tbody tr {
    white-space: nowrap;
}

.center-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    width: 100%;
}
button {
    background-color: <?= $config['cell_latest_color'] ?>;
    color: <?= $config['font_cell_latest_color'] ?>;
    border-radius: 15px;
    border: 10px solid <?= $config['border_cell_latest_color'] ?>;
}
</style>
    <div class="row">
	    <div class="col-sm-10 col-sm-offset-1" style="text-align: center;">
	        <h1 class="text-header text-warning" style="color: <?= $config['title_right_color']; ?>;"><?= $config['title_right'] ?></h1>
	    </div>
	</div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <table class="table table-display2" id="table-display2" width="100%"> 
                <thead> 
                    <tr> 
                        <th style=""></th>
                    </tr> 
                </thead>
            </table>
        </div>
    </div>
<?php
echo Datatables::widget([
    'id' => 'table-display2',
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/display/data-display-lastq',
            'data' => [
            	'config' => $config
            ],
            'type' => 'POST'
        ],
        "dom" => "t",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ ",
            "emptyTable" => "-"
        ]),
        "pageLength" => empty($config['display_limit']) ? -1 : $config['display_limit'],
        "autoWidth" => false,
        "deferRender" => true,
        "info" => false,
        "ordering" => false,
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                $("#table-display2 thead").hide();
            }
        '),
        'columns' => [
            [
                "data" => "qnum",
                "defaultContent" => '<button class="width-fixed">A001</button> <button class="width-fixed">A001</button> <button class="width-fixed">-</button>',
                "width" => "33.33%",
                "className" => "dt-center dt-head-nowrap width-fixed",
            ],
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

<?php
$this->registerJs(<<<JS
socket
.on('display', (res) => {
	if(Display.checkService(res)) {
        Display.reloadDisplay();
    }
});
Display = {
    reloadDisplay: function(){
        dt_tabledisplay2.ajax.reload();//โหลดข้อมูลแสดงผล
    },
    checkService: function(res){
        if( jQuery.inArray((res.artist.data.serviceid).toString(), config.service_id) != -1 && jQuery.inArray((res.artist.counter.counterservice_type).toString(), config.counterservice_id) != -1) {
            return true;
        }else{
            return false;
        }
    }
};
JS
);
?>