<?php

use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Tabs;

$this->title = 'จัดการร้านขายยา';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel-body">
    <div class="table-responsive">
        <?php
        echo Table::widget([
            'tableOptions' => ['class' => 'table table-hover', 'width' => '100%', 'id' => 'get_pharmacy_drug'],
            'beforeHeader' => [
                [
                    'columns' => [
                        ['content' => '#', 'options' => []],
                        ['content' => 'ชื่อร้ายขายยา', 'options' => []],
                        ['content' => 'ที่อยู่', 'options' => []],
                        ['content' => 'วันที่สร้างรายการ', 'options' => []],
                      //  ['content' => 'วันที่ปรับปรุงรายการ', 'options' => []],
                        ['content' => 'สถานะ', 'options' => []],
                        ['content' => 'Action', 'options' => []],
                    ]
                ]
            ],
        ]);
        ?>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'get_pharmacy_drug',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => 'data-pharmacy',
            'method' => 'get'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "language" => array_merge(Yii::$app->params['dtLanguage'], [
            "search" => "_INPUT_ " . Html::a(Icon::show('plus').' เพิ่มรายการ', ['drug-dispensing/create-pharmacy'],['class' => 'btn btn-success','role' => 'modal-remote']),
            "searchPlaceholder" => "ค้นหา..."
        ]),
        "pageLength" => 50,
        "lengthMenu" => [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        "autoWidth" => false,
        "deferRender" => true,
        'initComplete' => new JsExpression('
            function () {
                var api = this.api();
                dtFnc.initResponsive( api );
                dtFnc.initColumnIndex( api );
            }
        '),
        'columns' => [
            ["data" => null, "defaultContent" => "", "className" => "dt-center dt-head-nowrap", "title" => "#", "orderable" => false],
            ["data" => "pharmacy_drug_name", "className" => "dt-body-left dt-head-nowrap", "title" => "ชื่อร้านขายา"],
            ["data" => "pharmacy_drug_address", "className" => "dt-body-left dt-head-nowrap", "title" => "ที่อยู่"],
            ["data" => "pharmacy_drug_date_create", "className" => "dt-body-left dt-head-nowrap", "title" => "วันที่สร้าง"],
           // ["data" => "pharmacy_drug_date_update", "className" => "dt-body-left dt-head-nowrap", "title" => "วันที่ปรับปรุง"],
            ["data" => "is_active", "className" => "dt-body-left dt-head-nowrap", "title" => "สถานะ"],
            ["data" => "actions", "className" => "dt-center dt-nowrap", "orderable" => false, "title" => "Action"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>