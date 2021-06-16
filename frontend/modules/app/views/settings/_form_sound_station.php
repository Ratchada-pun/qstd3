<?php

use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\icons\Icon;

$this->registerCss('
.modal-dialog{
	width: 90%;
}
.modal-header{
	padding: 10px;
}
');
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-sound-station', 'type' => ActiveForm::TYPE_HORIZONTAL,
    'formConfig' => ['showLabels' => false],
]); ?>
<div class="form-group">
    <?= Html::activeLabel($model, 'sound_station_name', ['label' => 'ชื่อ', 'class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'sound_station_name', ['showLabels' => false])->textInput([]); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'counterserviceid', ['label' => 'ช่องบริการ', 'class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-10">
        <?= $form->field($model, 'counterserviceid', ['showLabels' => false])->checkBoxList(ArrayHelper::map((new \yii\db\Query())
            ->select(['tb_counterservice.counterserviceid', 'concat(tb_counterservice_type.counterservice_type, \' : \', tb_counterservice.counterservice_name) as counterservice_name'])
            ->from('tb_counterservice')
            ->innerJoin('tb_counterservice_type', 'tb_counterservice_type.counterservice_typeid = tb_counterservice.counterservice_type')
            ->where(['tb_counterservice.counterservice_status' => 1])
            ->all(), 'counterserviceid', 'counterservice_name'), [
            'inline' => false,
            'item' => function ($index, $label, $name, $checked, $value) {

                $return = '<div class="checkbox"><label style="font-size: 1em">';
                $return .= Html::checkbox($name, $checked, ['value' => $value]);
                $return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
                $return .= '</label></div>';

                return $return;
            },
        ]); ?>
    </div>
</div>

<div class="form-group">
    <?= Html::activeLabel($model, 'sound_station_status', ['label' => 'สถานะ', 'class' => 'col-sm-2 control-label']) ?>
    <div class="col-sm-4">
        <?= $form->field($model, 'sound_station_status', ['showLabels' => false])->widget(Select2::classname(), [
            'data' => [0 => 'Disabled', 1 => 'Enabled'],
            'options' => ['placeholder' => 'สถานะ...'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'theme' => Select2::THEME_BOOTSTRAP,
        ]) ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-12" style="text-align: right;">
        <?= Html::button(Icon::show('close') . 'CLOSE', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']); ?>
        <?= Html::submitButton(Icon::show('save') . 'SAVE', ['class' => 'btn btn-primary']); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(
    <<<JS
var table = $('#tb-sound-station').DataTable();
var \$form = $('#form-sound-station');
\$form.on('beforeSubmit', function() {
    var data = new FormData($(\$form)[0]);//\$form.serialize();
    var \$btn = $('button[type="submit"]').button('loading');//loading btn
    \$.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        async: false,
        processData: false,
        contentType: false,
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
                setTimeout(function(){
                    \$btn.button('reset');
                }, 1000);//clear button loading
            }else if(data.validate != null){
                $.each(data.validate, function(key, val) {
                    $(\$form).yiiActiveForm('updateAttribute', key, [val]);
                });
                \$btn.button('reset');
            }
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