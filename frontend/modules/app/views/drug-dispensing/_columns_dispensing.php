<?php

use homer\widgets\Table;
use homer\widgets\Datatables;
use kartik\form\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\icons\Icon;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\Tabs;

$this->title = 'รายการยา';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.modal-header {
    padding: 15px 30px;
    background: #f7f9fa;
}
.modal-title {
    font-size: 20px;
    font-weight: 300;
}
.modal-title {
    font-size: 22px;
    font-weight: 300;
}
.modal-title {
    font-size: 20px;
    font-weight: 300;
}
');

?>


<div class="panel-body">
    <?php $form = ActiveForm::begin(['id' => 'form-update-dispensing', 'type' => ActiveForm::TYPE_HORIZONTAL,]); ?>

    <div class="form-group row">
        <?= Html::label('เลขที่ใบสั่งยา', '', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-3">
            <?php echo Html::input('text', 'rx_operator_id', $model['rx_operator_id'], [
                'class' => 'form-control',
                'readonly' => true
                ])
            ?>
        </div>

        <?= Html::label('ชื่อร้านยา', '', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-5">
            <?php echo Html::input('text', 'pharmacy_drug_name', $model['pharmacy_drug_name'], [
                'class' => 'form-control',
                'readonly' => true
                ]) 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <?= Html::label('HN', '', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-3">
            <?php echo Html::input('text', 'HN', $model['HN'], ['class' => 'form-control','readonly' => true]) ?>
        </div>

        <?= Html::label('ชื่อผู้รับบริการ', '', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-5">
            <?php echo Html::input('text', 'pt_name', $model['pt_name'], ['class' => 'form-control','readonly' => true]) ?>
        </div>
    </div>

    <div class="form-group row">
        <?= Html::label('วันที่สั่งยา', '', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-3">
            <?php echo Html::input('text', 'prescription_date', Yii::$app->formatter->asDate($model['prescription_date'], 'php:d/m/Y'), [
                 'class' => 'form-control',
                  'readonly' => true,
                ])
            ?>
        </div>

        <?= Html::label('แพทย์ผู้สั่งยา', '', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-5">
            <?php echo Html::input('text', 'doctor_name', $model['doctor_name'], [
                'class' => 'form-control',
                'readonly' => true
                ]) 
            ?>
        </div>
    </div>

    <div class="form-group row">
        <?= Html::activeLabel($model, 'note', ['class' => 'col-sm-2 control-label']) ?>
        <div class="col-md-10">
            <?php echo  $form->field($model, 'note',['showLabels'=> false])->textarea()->label(false) ?>
        </div>
    </div>

    <?php if (Yii::$app->controller->action->id == 'update-dispensing') : ?>
        <div class="form-group row">
            <div class="col-md-12 text-right">
                <?= Html::a('จ่ายยา', ['/app/drug-dispensing/update-dispensing', 'id' => $model['dispensing_id']], [
                    'class' => 'btn btn-success', 
                    'id' => 'update'
                    ])
                ?>
                <?= Html::button('ปิดหน้า', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
            </div>
        </div>
    <?php endif; ?>


    <?php if (Yii::$app->controller->action->id == 'cancel-dispensing') : ?>
        <div class="form-group row">
            <div class="col-md-12 text-right">
                <?= Html::a('ยกเลิกจ่ายยา', ['/app/drug-dispensing/cancel-dispensing', 'id' => $model['dispensing_id']], ['class' => 'btn btn-success', 'id' => 'cancel']) ?>
                <?= Html::button('ปิดหน้า', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>
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
            'url' => '/app/drug-dispensing/data-rx-detail?rx_number=' . $model['rx_operator_id'],
            'method' => 'get'
        ],
        //"dom" => "<'pull-left'f><'pull-right'l>t<'pull-left'i>p",
        "pageLength" => 25,
        "lengthMenu" => [[10, 25, 50, 75, 100], [10, 25, 50, 75, 100]],
        "autoWidth" => false,
        "deferRender" => true,
        "searching" => false,
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

<?php
$this->registerJs(
    <<<JS
var \$form = $('#form-update-dispensing');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            var table = $('#tb-drug-dispensing').DataTable();
            table.ajax.reload();
            var table2 = $('#tb-drug-dispensing2').DataTable();
            table2.ajax.reload();
            $('#ajaxCrudModal').modal('hide');
        },
        error: function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
     return false; // prevent default submit
});

$("#update").on('click',function(message, ok, cancel){
    var url = $(this).attr('href')
    var data = \$form.serialize();
    swal({
        title: 'ยืนยันจ่ายยา?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'จ่ายยา',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function () {
                        var table = $('#tb-drug-dispensing').DataTable();
                        table.ajax.reload();
                        var table2 = $('#tb-drug-dispensing2').DataTable();
                        table2.ajax.reload();
                        $('#ajaxCrudModal').modal('hide');
                        resolve()
                    },
                    error: function(jqXHR, errMsg) {
                        swal(
                            'Oops!',
                            errMsg,
                            'error'
                        )
                    }
                });
            })
        }
    }).then((result) => {
        if (result.value) {
            swal(
                '',
                'จ่ายยาสำเร็จ!',
                'success'
            )
        }
    })
    return false;
});
$("#cancel").on('click',function(message, ok, cancel){
    var url = $(this).attr('href')
    var data = \$form.serialize();
    swal({
        title: 'ยกเลิกจ่ายยา?',
        text: "",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ยกเลิกจ่ายยา',
        cancelButtonText: 'ยกเลิก',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function () {
                        var table = $('#tb-drug-dispensing').DataTable();
                        table.ajax.reload();
                        var table2 = $('#tb-drug-dispensing2').DataTable();
                        table2.ajax.reload();
                        $('#ajaxCrudModal').modal('hide');
                        resolve()
                    },
                    error: function(jqXHR, errMsg) {
                        swal(
                            'Oops!',
                            errMsg,
                            'error'
                        )
                    }
                });
            })
        }
    }).then((result) => {
        if (result.value) {
            swal(
                '',
                'ยกเลิกจ่ายยาสำเร็จ!',
                'success'
            )
        }
    })
    return false;
});
JS
);
?>