<?php

use homer\widgets\Table;
use homer\widgets\Datatables;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Tabs;

$this->title = 'ประวัติรายการยา';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.modal-header {
    padding: 15px 30px;
    background: #f7f9fa;
}
');
?>


<div class="panel-body">
    <div class="table-responsive">
        <?php
        echo Table::widget([
            'tableOptions' => ['class' => 'table table-hover', 'width' => '100%', 'id' => 'get_rx_detail'],
            'beforeHeader' => [
                [
                    'columns' => [
                        ['content' => '#', 'options' => []],
                        ['content' => 'รหัสยา', 'options' => []],
                        ['content' => 'ชื่อยา', 'options' => []],
                        ['content' => 'จำนวน', 'options' => []],
                        ['content' => 'หน่วย', 'options' => []],
                        ['content' => 'วิธีใช้ยา', 'options' => []],
                        ['content' => 'คำเตือนการใช้ยา', 'options' => []],
                       
                    ]
                ]
            ],
        ]);
        ?>
    </div>
</div>

<?= Datatables::widget([
    'id' => 'get_rx_detail',
    'select2' => true,
    'clientOptions' => [
        'ajax' => [
            'url' => '/app/drug-dispensing/data-rx-detail?rx_number='.$rx_number,
            'method' => 'get'
        ],
        "dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "pageLength" => 25,
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
            ["data" => "drug_code", "className" => "dt-body-left dt-head-nowrap", "title" => "รหัสยา"],
            ["data" => "drug_name", "className" => "dt-body-left dt-head-nowrap", "title" => "ชื่อยา"],
            ["data" => "qty", "className" => "dt-body-left dt-head-nowrap", "title" => "จำนวน"],
            ["data" => "drug_unit", "className" => "dt-body-left dt-head-nowrap", "title" => "หน่วย"],
            ["data" => "drug_seq", "className" => "dt-body-left dt-head-nowrap", "title" => "วิธีใช้ยา"],
            ["data" => "drug_warning", "className" => "dt-body-left dt-head-nowrap", "title" => "คำเตือนการใช้ยา"]
        ],
    ],
    'clientEvents' => [
        'error.dt' => 'function ( e, settings, techNote, message ){
            e.preventDefault();
            swal({title: \'Error...!\',html: \'<small>\'+message+\'</small>\',type: \'error\',});
        }'
    ]
]); ?>