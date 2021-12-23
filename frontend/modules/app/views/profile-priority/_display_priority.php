<?php

use frontend\assets\ModernBlinkAsset;
use frontend\modules\app\models\TbCounterservice;
use frontend\modules\app\models\TbService;
use homer\assets\DatatablesAsset;
use homer\assets\SocketIOAsset;
use homer\assets\SweetAlert2Asset;
use homer\assets\ToastrAsset;
use homer\widgets\Datatables;
use kartik\depdrop\DepDrop;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = 'Priority';

$this->registerCss(
    <<<CSS
body {
    color: #333333;
    background-color: #0baabd;
}
body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
  overflow-y: unset !important;
}

.swal2-shown {
  overflow-x: auto !important;
}

.swal2-container.swal2-center.swal2-backdrop-show {
  overflow-y: auto !important;
}
.swal2-popup {
  font-size: 1.6rem !important;
}
.swal2-html-container {
    font-size: 5rem!important;
}
#logo.light-version {
    background-color: #ffffff;
    border-bottom: 1px solid #ffffff;
    text-align: center;
}
#logo {
    float: left;
    width: 180px;
    background-color: #34495e;
    padding: 0;
    height: 100px;
    text-align: center;
}
.btn-service {
    height: 90px;
    border-radius: 1.75rem;
    border: 2px solid #ffa800;
    font-size: 3rem;
    font-weight: 600;
}
.content {
    padding: 10px 40px 40px 40px;
    min-width: 320px;
}
/* .normalheader {
    display:none;
} */
body.blank {
    background-color: #d1c4e9;
}
#wrapper {
    background: #d1c4e9;
}
CSS
);

$this->registerCss($this->render('./css/display.css'));
$this->registerCss('
    table.table-display2 thead tr {
        width: 50%;
        border-radius: 15px;
        border: 5px solid white;
        background-color: #e1bee7;
        color: #000000;
        font-weight: bold;
    }
    table.table-display2 tbody tr {
        width: 50%;
        border-radius: 15px;
        border: 5px solid white;
        background-color: #b39ddb;
        color: #ffffff;
        font-weight: bold;
    }

    table.table-display2 tbody tr td.dataTables_empty {
        border-radius: 15px;
        border: 5px solid white;
        background-color: #b39ddb;
        color: #ffffff;
        font-weight: bold;
    }
    
    /*  */
    table.table-display2 tbody tr td.td-left{
        border-top: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-bottom: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-left: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    table.table-display2 tbody tr td.td-right{
        border-top: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-bottom: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-right: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    table.table-display2 thead tr th.th-right{
        border-top: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-bottom: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-right: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    table.table-display2 thead tr th.th-left{
        border-top: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-bottom: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-left: 5px solid ' . $display['border_cell_latest_color'] . ' !important;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .vertical-align-middle{
        vertical-align: middle !important;
    }
    .blink {
        animation: blinker 1s linear infinite;
    }
    
    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }
');
?>


<div class="row">
    <div class="col-md-8">
        <?php $form = ActiveForm::begin(['id' => 'form-priority']); ?>
        <?php Pjax::begin(['id' => 'pjax-form']) ?>
        <table cellpadding="1" cellspacing="1" class="table table-bordered">
            <thead>
                <tr>
                    <th class="h-bg-violet text-white" style="text-align: center;">#</th>
                    <th class="h-bg-violet text-white" style="text-align: center;">ช่องบริการ</th>
                    <?php for ($i = 0; $i < $cols; $i++) { ?>
                        <th class="h-bg-violet text-white" style="text-align: center;">Priority <?= $i + 1 ?></th>
                    <?php  } ?>
                    <th class="h-bg-violet text-white" style="text-align: center;">จำนวนคิวรอ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($models as $index => $model) { ?>
                    <?php
                    $lastIndex = 0;
                    ?>
                    <tr style="background-color: #ffffff;">
                        <td style="text-align: center;">
                            <?= $index + 1 ?>
                            <?php
                            // necessary for update action.
                            if (!$model->isNewRecord) {
                                echo Html::activeHiddenInput($model, "[{$index}]service_profile_id");
                            }
                            ?>
                        </td>
                        <td>
                            <?= $form->field($model, "[$index]counterserviceid", ['showLabels' => false])->widget(Select2::classname(), [
                                'data' => ArrayHelper::map(TbCounterservice::find()->where(['counterservice_type' => $model['counterservice_typeid']])->asArray()->all(), 'counterserviceid', 'counterservice_name'),
                                'options' => ['placeholder' => 'Select ...'],
                                'pluginOptions' => ['allowClear' => true],
                                'theme' => Select2::THEME_BOOTSTRAP,
                                'disabled' => true
                            ]); ?>
                        </td>
                        <?php foreach ($model->profilePrioritys as $subindex => $modelPri) { ?>
                            <td>
                                <?= $form->field($modelPri, "[{$index}][{$subindex}]service_id", ['showLabels' => false])->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(TbService::find()->asArray()->all(), 'serviceid', 'service_name'),
                                    'options' => ['placeholder' => 'Select ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                        'width' => '150px'
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]); ?>
                                <?php
                                // necessary for update action.
                                if (!$modelPri->isNewRecord) {
                                    echo Html::activeHiddenInput($modelPri, "[{$index}][{$subindex}]profile_priority_id");
                                }
                                ?>
                            </td>
                            <?php
                            $lastIndex = $subindex + 1;
                            ?>
                            <?php if ($lastIndex < $cols && $lastIndex == count($model->profilePrioritys)) { ?>
                                <?php for ($i = $lastIndex; $i < $cols; $i++) {  ?>
                                    <td>
                                        <?php /*
                                <?= $form->field($modelPri, "[{$index}][{$i}]service_id", ['showLabels' => false])->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(TbService::find()->asArray()->all(), 'serviceid', 'service_name'),
                                    'options' => ['placeholder' => 'Select ...'],
                                    'pluginOptions' => ['allowClear' => true],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                ]); ?>
                                */ ?>
                                    </td>
                                <?php  } ?>
                            <?php  } ?>
                        <?php  } ?>

                        <td class="text-center">
                            <span id="profile-count-<?php echo $model->service_profile_id ?>"><?php echo $model->countWaiting ?></span>
                        </td>
                    </tr>
                <?php  } ?>
            </tbody>
        </table>
        <?php Pjax::end() ?>
        <div class="row">
            <div class="col-md-12 text-right">
                <?= Html::submitButton('บันทึก', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-4" style="padding: 0;">
        <table class="table table-display2" id="table-display2" width="100%">
            <thead>
                <tr>
                    <th style="width: 50%;" class="th-left">หมายเลข</th>
                    <th style="width: 50%;" class="th-right">ช่อง</th>
                </tr>
            </thead>
            <tbody>
                <tr id="A" data-key="A" role="row" class="odd">
                    <td class=" dt-center dt-head-nowrap th-left td-left">1234</td>
                    <td class=" dt-body-center dt-head-nowrap th-right td-right">7</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>



<br>
<br>


<?php
SweetAlert2Asset::register($this);
DatatablesAsset::register($this);
SocketIOAsset::register($this);
ToastrAsset::register($this);

$js = <<<JS
var \$form = $('#form-priority');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            $.pjax.reload({container:"#pjax-form"});
            swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
        },
        error: function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
     return false; // prevent default submit
});


var table = $('#table-display2').DataTable({
    dom: "t",
    ajax: {
        url: "/app/profile-priority/data-display",
        type: "GET",
    },
    language: {
        emptyTable: '-'
    },
    pageLength: 5,
    autoWidth: false,
    deferRender: true,
    ordering: false,
    order: [[0, "asc"]],
    info: false,
    columns: [
        {
            data: "q_num",
            className: "dt-center dt-head-nowrap th-left td-left",
            orderable: false,
        },
        {
            data: "counterservice_callnumber",
            className: "dt-body-center dt-head-nowrap th-right td-right",
            orderable: false,
        }
    ]
});

socket
    .on("register", (res) => {
        table.ajax.reload();
        getCountWaiting()
    })
    .on("setting", (res) => {
        table.ajax.reload();
        $.pjax.reload({container:"#pjax-form"});
    })
    .on("call", (res) => {
        table.ajax.reload();
        getCountWaiting()
    })
    .on("hold", (res) => {
        table.ajax.reload();
        getCountWaiting()
    })
    .on("end", (res) => {
        table.ajax.reload();
        getCountWaiting()
    })

    function getCountWaiting() {
        $.ajax({
            url: '/app/profile-priority/count-waiting',
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                for (let i = 0; i < result.length; i++) {
                    const data = result[i];
                    $('#profile-count-' + data.service_profile_id).html(data.count)
                }
            },
            error: function(jqXHR, errMsg) {
                alert(errMsg);
            }
        });
    }
JS;

$this->registerJs($js);
?>