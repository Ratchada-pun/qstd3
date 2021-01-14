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
                    ['content' => 'ID','options' => []],
                    ['content' => 'ชื่อบริการ','options' => []],
                    ['content' => 'ประเภทเคาน์เตอร์','options' => []],
                    ['content' => 'แผนก','options' => []],
                    ['content' => 'Sound Station','options' => []],
                    ['content' => 'Sound Type','options' => []],
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
            'url' => Url::base(true).'/settings/default/data-counter'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่ม-ลบ รายการ', ['/settings/default/create-counter'],['class' => 'btn btn-success','role' => 'modal-remote']),
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
            ["data" => null,"defaultContent" => "", "className" => "dt-center dt-head-nowrap","title" => "#", "orderable" => false],
            ["data" => "counterservice_name","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อบริการ"],
            ["data" => "tb_counterservice_type","className" => "dt-body-left dt-head-nowrap","title" => "ประเภทเคาน์เตอร์"],
            ["data" => "sec_name","className" => "dt-body-left dt-head-nowrap","title" => "แผนก"],
            ["data" => "sound_stationid","className" => "dt-body-left dt-head-nowrap","title" => "Sound Station"],
            ["data" => "sound_typeid","className" => "dt-body-left dt-head-nowrap","title" => "Sound Type"],
            ["data" => "counterservice_status","className" => "dt-body-left dt-head-nowrap","title" => "สถานะ"],
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