<?php
use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\icons\Icon;
use kartik\widgets\Select2;
use frontend\modules\kiosk\models\TbCounterserviceType;
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
    'id' => 'form-display', 'type' => ActiveForm::TYPE_HORIZONTAL, 
    'formConfig' => ['showLabels' => false],
]);?>
	<div class="form-group">
	    <?= Html::activeLabel($model, 'display_name', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'display_name',['showLabels'=>false])->textInput([]); ?>
	    </div>

	    <?= Html::activeLabel($model, 'counterservice_type', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
            <?= $form->field($model, 'counterservice_type')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(TbCounterserviceType::find()->asArray()->all(),'tb_counterservice_typeid','tb_counterservice_type'),
                'options' => ['placeholder' => 'Select Counter...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'theme' => Select2::THEME_BOOTSTRAP,
            ])?>
        </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'title_left', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'title_left',['showLabels'=>false])->textInput([]); ?>
	    </div>

	    <?= Html::activeLabel($model, 'title_right', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'title_right',['showLabels'=>false])->textInput([]); ?>
	    </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'table_title_left', ['label' => 'Header Left','class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'table_title_left',['showLabels'=>false])->textInput([]); ?>
	    </div>

	    <?= Html::activeLabel($model, 'table_title_right', ['label' => 'Header Right','class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'table_title_right',['showLabels'=>false])->textInput([]); ?>
	    </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'display_limit', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'display_limit',['showLabels'=>false])->textInput([]); ?>
	    </div>

	    <?= Html::activeLabel($model, 'hold_label', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'hold_label',['showLabels'=>false])->textInput([]); ?>
	    </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'header_color', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'header_color')->widget(ColorInput::classname(), [
			    'options' => ['placeholder' => 'Select color ...'],
			]) ?>
	    </div>

	    <?= Html::activeLabel($model, 'column_color', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'column_color')->widget(ColorInput::classname(), [
			    'options' => ['placeholder' => 'Select color ...'],
			]) ?>
	    </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'background_color', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'background_color')->widget(ColorInput::classname(), [
			    'options' => ['placeholder' => 'Select color ...'],
			]) ?>
	    </div>

	    <?= Html::activeLabel($model, 'font_color', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'font_color')->widget(ColorInput::classname(), [
			    'options' => ['placeholder' => 'Select color ...'],
			]) ?>
	    </div>
	</div>

	<div class="form-group">
	    <?= Html::activeLabel($model, 'border_color', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'border_color')->widget(ColorInput::classname(), [
			    'options' => ['placeholder' => 'Select color ...'],
			]) ?>
	    </div>

	    <?= Html::activeLabel($model, 'title_color', ['class'=>'col-sm-2 control-label']) ?>
	    <div class="col-sm-4">
	        <?= $form->field($model, 'title_color')->widget(ColorInput::classname(), [
			    'options' => ['placeholder' => 'Select color ...'],
			]) ?>
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
var table = $('#tb-display').DataTable();
var \$form = $('#form-display');
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