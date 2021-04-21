<?php

use kartik\checkbox\CheckboxX;
use kartik\form\ActiveForm;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\icons\Icon;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-service-slot',
    'type' => ActiveForm::TYPE_HORIZONTAL,
]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <?= $form->field($model, 'schedules')->widget(MultipleInput::className(), [
            'min' => 0,
            'max' => 10,
            'columns' => [
                [
                    'name'  => 't_slot_begin',
                    'title' => 'ช่วงเวลาเริ่ม',
                    'type' => \yii\widgets\MaskedInput::className(),
                    'options' => [
                        'mask' => '99:99',
                        'options' => [
                            'class' => 'form-control'
                        ],

                    ],
                    'enableError' => true,
                ],
                [
                    'name'  => 't_slot_end',
                    'title' => 'ช่วงเวลาสิ้นสุด',
                    'type' => \yii\widgets\MaskedInput::className(),
                    'options' => [
                        'mask' => '99:99',
                        'options' => [
                            'class' => 'form-control'
                        ],
                    ],
                    'enableError' => true,
                ],
                [
                    'name'  => 'q_limit',
                    'type'  => CheckboxX::class,
                    'title' => 'จำกัดคิว',
                    'defaultValue' => 1,
                    'options' => [
                        'pluginOptions' => ['threeState' => false],
                    ],
                    'headerOptions' => [
                        'style' => 'text-align:center',
                    ],
                    'enableError' => true,
                ],
                [
                    'name'  => 'q_limitqty',
                    'type'  => 'textInput',
                    'title' => 'จำนวนคิว',
                    'enableError' => true,
                ],
            ]
        ]);
        ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12" style="text-align: right;">
        <div class="form-group">
            <div class="col-sm-12">
                <?= Html::button(Icon::show('close') . 'CLOSE', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
                <?= Html::submitButton(Icon::show('save') . 'SAVE', ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>


<?php
$this->registerJs(<<<JS
//Form Event
var table = $('#tb-service-group').DataTable();
var \$form = $('#form-service-slot');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    var \$btn = $('button[type="submit"]').button('loading');//loading btn
    \$.ajax({
        url: \$form.attr('action'),
        type: \$form.attr('method'),
        data: data,
        dataType:'json',
        // async: false,
        // processData: false,
        // contentType: false,
        success: function (data) {
            if(data.status == '200'){
                $('#ajaxCrudModal').modal('hide');//hide modal
                table.ajax.reload();//reload table
                swal({//alert completed!
                    type: 'success',
                    title: 'บันทึกสำเร็จ!',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function(){ \$btn.button('reset'); }, 1500);//clear button loading
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
            }
            \$btn.button('reset');
        },
        error: function(jqXHR, errMsg) {
            swal('Oops...',errMsg,'error');
            \$btn.button('reset');
        }
    });
    return false; // prevent default submit
});
JS
);
?>