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
        'tableOptions' => ['class' => 'table table-hover','width' => '100%','id' => 'tb-qreset'],
        'beforeHeader' => [
            [
                'columns' => [
                    ['content' => '#','options' => []],
                    ['content' => 'หมายเลขคิว','options' => []],
                    ['content' => 'ชื่อ-นามสกุล','options' => []],
                    ['content' => 'วันที่ออกบัตรคิว','options' => []],
                ]
            ]
        ],
    ]);
    ?>
</div>

<?= Datatables::widget([
    'id' => 'tb-qreset',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => Url::base(true).'/app/settings/data-qreset'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'],[
            "search" => "_INPUT_ " . Html::a('Reset',['/app/settings/reset-qdata'],['class' => 'btn btn-danger activity-reset']),
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
            ["data" => "q_num","className" => "dt-body-left ","title" => "หมายเลขคิว"],
            ["data" => "pt_name","className" => "dt-body-left dt-nowrap","title" => "ชื่อ-นามสกุล"],
            ["data" => "q_timestp","className" => "dt-body-left ","title" => "วันที่ออกบัตรคิว"],
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
    $('a.activity-reset').on('click',function(e){
        event.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    method: "POST",
                    url: "/app/settings/reset-qdata",
                    dataType: "json",
                    success: function(res){
                        dt_tbqreset.ajax.reload();
                    },
                    error:function(jqXHR, textStatus, errorThrown){
                        swal({
                            type: 'error',
                            title: errorThrown,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    });
JS
);
?>