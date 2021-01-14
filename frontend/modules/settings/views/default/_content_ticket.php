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
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-ticket'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => 'ID','options' => []],
                    ['content' => '','options' => []],
                    ['content' => '','options' => []],
                    ['content' => '','options' => []],
                    ['content' => '','options' => []],
                    ['content' => 'ดำเนินการ','options' => []],
                ]
            ]
        ],
    ]);
    ?>
</div>

<?= Datatables::widget([
    'id' => 'tb-ticket',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/settings/default/data-ticket'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่มรายการ', ['/settings/default/create-ticket'],['class' => 'btn btn-success','role' => 'modal-remote']),
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
            ["data" => "hos_name_th","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อโรงพยาบาล(ไทย)"],
            ["data" => "hos_name_en","className" => "dt-body-left dt-head-nowrap","title" => "ชื่อโรงพยาบาล(อังกฤษ)"],
            ["data" => "barcode_type","className" => "dt-body-left dt-head-nowrap","title" => "รหัสบาร์โค้ดที่ใช้"],
            ["data" => "status","className" => "dt-body-left dt-head-nowrap","title" => "สถานะการใช้งาน"],
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
$('#ajaxCrudModal').on('hidden.bs.modal', function (e) {
    //location.reload();
});
JS
);
?>