<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\icons\Icon;
use kartik\widgets\Select2;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbCounterserviceType;
use yii\helpers\ArrayHelper;
use kartik\widgets\ColorInput;

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
    'id' => 'form-kiosk', 'type' => ActiveForm::TYPE_HORIZONTAL, 
    'formConfig' => ['showLabels' => false],
]);?>
	<div class="form-group">
	    <?= Html::activeLabel($model, 'kiosk_name', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'kiosk_name',['showLabels'=>false])->textInput([]); ?>
	    </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'service_ids', ['label' => 'กลุ่มบริการ','class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-4">
			<?= $form->field($model, 'service_ids',['showLabels'=>false])->checkBoxList(
				ArrayHelper::map((new \yii\db\Query())
                ->select([
                    'tb_service.serviceid',
                    'CONCAT(IFNULL(tb_service.btn_kiosk_name,\'\'),\' (\',IFNULL(tb_service.service_name,\'\'),\')\') AS service_name'
                ])
                ->from('tb_service')
                ->where(['tb_service.show_on_kiosk' => 1, 'tb_service.service_status' => 1])
                ->all(),'serviceid','service_name'),[
            	'inline'=>false,
				'item' => function($index, $label, $name, $checked, $value) {

					$return = '<div class="checkbox"><label style="font-size: 1em">';
					$return .= Html::checkbox( $name, $checked,['value' => $value]);
					$return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
					$return .= '</label></div>';

					return $return;
				}
            ]); ?>
        </div>
	</div>

    <div class="form-group">
        <?= Html::activeLabel($model, 'font_size', ['label' => 'ขนาดตัวอักษรปุ่มกด','class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-2">
			<?= $form->field($model, 'font_size',['showLabels'=>false])->textInput(); ?>
        </div>
    </div>
	

	<div class="form-group">
        <?= Html::activeLabel($model, 'status', ['label' => 'สถานะ','class'=>'col-sm-2 control-label']) ?>
        <div class="col-sm-4">

			<?= $form->field($model, 'status',['showLabels'=>false])->RadioList(
				[0 => 'ปิดใช้งาน', 1 => 'เปิดใช้งาน'],
				[
            	'inline'=>true,
				'item' => function($index, $label, $name, $checked, $value) {

					$return = '<div class="radio"><label style="font-size: 1em">';
					$return .= Html::radio( $name, $checked,['value' => $value]);
					$return .= '<span class="cr"><i class="cr-icon cr-icon glyphicon glyphicon-ok"></i></span>' . ucwords($label);
					$return .= '</label></div>';

					return $return;
				}
            ]); ?>
        </div>
    </div>

	<div class="form-group">
        <div class="col-sm-12" style="text-align: right;">
            <?= Html::button(Icon::show('close').'CLOSE',['class' => 'btn btn-default','data-dismiss' => 'modal']); ?>
            <?= Html::submitButton(Icon::show('save').'SAVE',['class' => 'btn btn-primary']); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
var table = $('#tb-kiosk').DataTable();
var \$form = $('#form-kiosk');
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