<?php

use kartik\form\ActiveForm;
use PHPUnit\Util\Log\JSON;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\icons\Icon;


$this->registerCss('
.modal-header {
    padding: 15px 30px;
    background: #f7f9fa;
}
.modal-title {
    font-size: 20px;
    font-weight: 300;
}
');
?>

<?php $form = ActiveForm::begin(['id' => 'form-pharmacy', 'type' => ActiveForm::TYPE_HORIZONTAL,]); ?>

<div class="form-group row">
    <?= Html::activeLabel($model, 'pharmacy_drug_name', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-md-8">
        <?php echo  $form->field($model, 'pharmacy_drug_name')->textInput()->label(false) ?>
    </div>
</div>
<div class="form-group row">
    <?= Html::activeLabel($model, 'pharmacy_drug_address', ['class' => 'col-sm-2 control-label']) ?>
    <div class="col-md-10">
        <?php echo  $form->field($model, 'pharmacy_drug_address')->textarea()->label(false) ?>
    </div>
</div>
<div class="form-group row">
    <div class="col-md-10 text-right">
        <?= Html::submitButton('บันทึก',['class' => 'btn btn-success']) ?>
        <?= Html::button('ปิด', ['class' => 'btn btn-default', 'data-dismiss' => "modal"]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php
$this->registerJs(<<<JS
var \$form = $('#form-pharmacy');
\$form.on('beforeSubmit', function() {
    var data = \$form.serialize();
    $.ajax({
        url: \$form.attr('action'),
        type: 'POST',
        data: data,
        success: function (data) {
            var table = $('#get_pharmacy_drug').DataTable();
            table.ajax.reload();
            $('#ajaxCrudModal').modal('hide');
        },
        error: function(jqXHR, errMsg) {
            alert(errMsg);
        }
     });
     return false; // prevent default submit
});
JS
);
?>