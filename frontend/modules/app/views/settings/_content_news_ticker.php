<?php
use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\switchinput\SwitchInputAsset;

SwitchInputAsset::register($this);
$this->registerCss(
 <<<CSS
 

/* The switch - the box around the slider */
.switch {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 19px;
}

/* Hide default HTML checkbox */
.switch input {display:none;}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 17px;
    left: 3px;
    bottom: 4px;
    top: 1px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
}

input.default:checked + .slider {
  background-color: #444;
}
input.primary:checked + .slider {
  background-color: #2196F3;
}
input.success:checked + .slider {
  background-color: #62cb31;
}
input.info:checked + .slider {
  background-color: #3de0f5;
}
input.warning:checked + .slider {
  background-color: #FFC107;
}
input.danger:checked + .slider {
  background-color: #f44336;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
CSS
);
?>
<div class="panel-body">
    <?php  
    echo Table::widget([
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-news-ticker'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#','options' => []],
                    ['content' => 'ข้อความ','options' => []],
                    ['content' => 'สถานะ','options' => []],
                    ['content' => 'action','options' => ['style' => 'text-align:center']],
                    ['content' => 'ดำเนินการ','options' => []],
                ]
            ]
        ],
    ]);
    ?>
</div>

<?= Datatables::widget([
    'id' => 'tb-news-ticker',
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/settings/data-news-ticker'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่มรายการ', ['/app/settings/create-news-ticker'],['class' => 'btn btn-success','role' => 'modal-remote']),
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [ [10, 25, 50, 75, 100], [10, 25, 50, 75, 100] ],
        "autoWidth" => false,
        "deferRender" => true,
        "order" => [[ 1, "asc" ]],
        "drawCallback" => new JsExpression ('function(settings) {
            var api = this.api();
            var rows = api.rows( {page:"current"} ).nodes();
            var columns = api.columns().nodes();
            var last=null;
            dtFnc.initConfirm(api);
        }'),
        'initComplete' => new JsExpression ('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
                initCheckbox();
            }
        '),
        'columns' => [
            ["data" => null,"defaultContent" => "", "className" => "dt-center dt-head-nowrap","title" => "#", "orderable" => false],
            ["data" => "news_ticker_detail","className" => "dt-body-left dt-head-nowrap","title" => "ข้อความ"],
            ["data" => "news_ticker_status","className" => "dt-center dt-nowrap","orderable" => false,"title" => "สถานะ"],
            ["data" => "news_ticker_status1","className" => "dt-center dt-nowrap","orderable" => false,"title" => "เปิด/ปิด ใช้งาน"],
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
$this->registerJs(<<<JS
function initCheckbox(){
    $('#tb-news-ticker tbody').on('change', 'input[type="checkbox"]', function(e){
        var value = 0;
        var id = $(this).data('key')
        if(e.target.checked){
            value = 1;
        }else{
            value = 0;
        }
        $.ajax({
            method: "post",
            url: "/app/settings/save-status-news-ticker",
            data:{
                value:value,
                id:id
            },
            dataType: "json",
            success:function(){
                var table = $('#tb-news-ticker').DataTable();
                table.ajax.reload();
                socket.emit('setting',{model:'TbNewsTicker'})
               // initCheckbox();
            },
            error:function(jqXHR,  textStatus,  errorThrown){
                alert(errorThrown)
            }
        });
    })
}
window.initCheckbox = initCheckbox
JS
);

?>